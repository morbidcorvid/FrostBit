<?php

Class Order_model extends CI_Model 
{
	public function insert_order($type, $account, $debit_credit)
	{
		$data = array(
			'type' => $type,
			'accounts_id' => $account,
			'debit_credit' => $debit_credit
		);
		$this->db->insert('orders',$data);
		return $this->db->insert_id();
	}
	
	public function insert_detail($order_id, $good_service_id, $ledger_accounts_id, $quantity, $cost_price_each)
	{
		$data = array(
			'order_id' => $order_id,
			'good_service_id' => $good_service_id,
			'ledger_accounts_id' => $ledger_accounts_id,
			'quantity' => $quantity,
			'cost_price_each' => $cost_price_each
		);
		$this->db->insert('details',$data);
		return $this->db->insert_id();
	}
	
	public function order_item($details)
	{
		$this->load->model('item_model');
		
		//insert order
		$order_id = $this->insert_order('Purchase', $details['account'], 1);
		
		//get the good purchased and payment
		$item = $this->item_model->get($details['item']);
		$payment = $this->item_model->get($details['payment']);
		
		//if financal item, cost defined in form
		$cost = ($item->class == 'finance' ? $details['cost'] : $item->cost);
		
		//credit payment
		$total = $details['quantity'] * $cost;
		$this->insert_detail($order_id, $details['payment'], $payment->ledger_out_id, -1, $total);
		
		//debit goods ledger in
		$this->insert_detail($order_id, $item->id, $item->ledger_in_id, $details['quantity'], $cost);
		
		return $order_id;
	}
	
	public function quick_sale($details)
	{
		//insert order
		$order_id = $this->insert_order('Purchase', $details['account'], 1);
		
		//get cash gs
		$payment = $this->item_model->get(1);
		
		$total = 0;
		foreach ($details['items'] as $item){
			//credit sales of goods
			$this->insert_detail($order_id, $item->id, $item->ledger_out_id, -($item->quantity), $item->price);
			
			//add to total
			$total += $item->quantity * $item->price;
		}
		
		//debit cash
		$this->insert_detail($order_id, $payment->id, $payment->ledger_in_id, 1, $total);
		
		return $order_id;
	}
}

?>