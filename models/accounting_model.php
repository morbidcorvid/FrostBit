<?php

Class Accounting_model extends CI_Model 
{

	public function get_order_dates()
	{
		$sql = "	SELECT DISTINCT DATE(order_date) as order_date
					FROM orders
					order by order_date";
		$query = $this->db->query($sql);
		return $query ;
	}
	
	public function get_journal_entry($date)
	{
		$sql = "	SELECT order_date, la.id, account_name, sum(quantity * cost_price_each) as net
					FROM details d, orders o, ledger_accounts la
					WHERE	d.order_id = o.id
							AND d.ledger_accounts_id = la.id
							AND Date(order_date) = ?
					GROUP BY la.id ORDER BY la.id";
		$query = $this->db->query($sql,array($date));
		return $query;
	}
	
	public function get_audit_trail($order_id = NULL)
	{
		$order = "";
		if ($order_id != NULL) {
			$order = "and o.id = $order_id";
		}
		$sql = "	SELECT o.id as 'Order ID', d.id as 'Detail ID', o.order_date as 'Order Date', 
						a.last_name as 'Account Name', gs.id as 'GS ID', gs.short_description as 'G/S Name', 
						d.cost_price_each, d.quantity, (d.quantity * d.cost_price_each) as Ext, 
						la.account_name 
					FROM orders o, details d, goods_services gs, ledger_accounts la, accounts a 
					WHERE 	d.good_service_id = gs.id 
							and d.ledger_accounts_id = la.id 
							and d.order_id = o.id 
							and o.accounts_id = a.id 
							$order
					ORDER BY o.order_date, d.id";
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function get_trial_balance()
	{
		$sql = "	SELECT la.id, account_name, sum(quantity * cost_price_each) as net 
					FROM details d, orders o, ledger_accounts la 
					WHERE	d.order_id = o.id 
							AND d.ledger_accounts_id = la.id 
					GROUP BY la.id 
					
					UNION
					
					SELECT '' as id, 'Net Total:' as account_name, sum(quantity * cost_price_each) as net
					FROM details
					
					ORDER BY id";
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function post_journal_entry($journal_entry, $date)
	{
		$date = date_format($date, "Ymd");
		$enterpriseID = '0124';
		$lines = 2;
      	$jv = "JV*$enterpriseID*$date\r\n";
		foreach($journal_entry->result() as $ledger)
		{
			$net = number_format($ledger->net,2,"","");
			$jv .= "NET*$ledger->id*$net*\r\n";
			$lines += 1;
		}
		$jv .= "EJV*$lines";
		
		return $this->post_to_ledger_engine($jv);
	}
	
	public function request_journals()
	{
      	$notetext = "REQUEST*JOURNALS";
		
		return $this->post_to_ledger_engine($notetext);
	}
	
	public function post_to_ledger_engine($notetext)
	{
		$enterpriseID = '0124';
		$subject_line = "*!*!$enterpriseID*". date("YmdHis") . "\r\n";
		$document = $subject_line . $notetext;
		$URL_Encoded_document = urlencode($document);
		$document_len = strlen($URL_Encoded_document);
		$ReceivingHost = 'info465.info';
      	$ReceivingScript = 'ledgerengine/psX12InWebSvc.php';
		$POSTString = "POST /$ReceivingScript HTTP/1.1\r\nHost: $ReceivingHost\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: $document_len\r\nConnection: close\r\n\r\n";
		
      	$Socket = fsockopen ("$ReceivingHost",80, $ErrorNumber, $ErrorString, 5);
      	if ($Socket) 
		{
			fwrite ($Socket,$POSTString.$URL_Encoded_document) or die("Unable to do fwrite...");
			$StringReceived = "";
			while (!feof($Socket)) 
			{
			  $StringReceived .= fgets($Socket,128);
			}
			fclose ($Socket);
			$data['POSTString'] = $POSTString.$document;
			$data['StringReceived'] = $StringReceived;
		} 
		else 
		{
			$data['ErrorNumber'] = $ErrorNumber;
			$data['ErrorString'] = $ErrorString;
		}
		
		return $data;
	}
}

?>