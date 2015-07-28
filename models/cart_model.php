<?php

Class Cart_model extends CI_Model 
{
	public function get($cart_id)
	{
		$sql = "select c.*, ch.cart_action as stutus, ch.account_id
				from carts c
				left join (
					select 		cart_action, cart_id, account_id
					from 		cart_history
					where 		cart_action like 'checked%'
					order by 	action_time desc
					limit 		1) ch
				on (c.id = ch.cart_id)
				where c.id = ?";
		$item = $this->db->query($sql,array($cart_id))->row();
		return $item;
	}
	
	public function get_all()
	{
		$sql = "select c.*, ch.cart_action as stutus, ch.account_id
				from carts c
				left join (
					select 		cart_action, cart_id, account_id
					from 		cart_history
					where 		cart_action like 'checked%'
					order by 	action_time desc
					limit 		1) ch
				on (c.id = ch.cart_id)
				order by id";
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function check_out($cart_id, $account_id)
	{
		$data = array(
			'cart_id' => $cart_id,
			'account_id' => $account_id,
			'cart_action' => 'checked out'
		);	
		return $this->db->insert('cart_history', $data);
	}
	
	public function check_in($cart_id, $account_id)
	{
		$data = array(
			'cart_id' => $cart_id,
			'account_id' => $account_id,
			'cart_action' => 'checked in'
		);	
		return $this->db->insert('cart_history', $data);
	}
	
	public function get_stocks($cart_id)
	{
		$sql = "select 	gs.*, cs.quantity as onhand
				from 	goods_services gs, cart_stock cs
				where 	gs.id = cs.gs_id
						and cart_id = ?";
		$query = $this->db->query($sql, array($cart_id));
		return $query;
	}
	
	public function get_all_stocks($cart_id)
	{
		$sql = "select 		gs.*, (sum(d.quantity) - ifnull(cs.quantity,0)) as stock, c.quantity
				from 		details d, goods_services gs
				left join 	(select gs_id, sum(quantity) as quantity from cart_stock GROUP by gs_id) cs
				on 			cs.gs_id = gs.id
				left join	(select gs_id, quantity from cart_stock where cart_id = ?) c
				on			c.gs_id = gs.id
				where 		gs.id = d.good_service_id
							and gs.class = 'good'
				group by 	gs.id";
		$query = $this->db->query($sql, array($cart_id));
		return $query;
	}
	
	public function update_stock($cart_id, $item_id, $quantity)
	{
		// get stock from cart_stock
		$query = $this->db->get_where('cart_stock', array('cart_id'=>$cart_id, 'gs_id'=>$item_id));
		// check that the item is already stocked, if so, update it
		if ($query->num_rows() > 0) 
		{
			$stock = $query->row();
			$where = array(
				'cart_id'=>$cart_id,
				'gs_id'=>$item_id
			);
			$new_quantity = $stock->quantity + $quantity;
			// update quantity if > 0
			if ($new_quantity > 0)
			{
				return $this->db->update('cart_stock', array('quantity'=>$new_quantity), $where);
			}
			// otherwise, delete it
			else
			{
				return $this->db->delete('cart_stock', $where);
			}
		}
		//else, add new record
		else
		{
			$data = array(
				'cart_id' => $cart_id,
				'gs_id' => $item_id,
				'quantity' => $quantity
			);
			return $this->db->insert('cart_stock', $data);
		}
	}
}

?>