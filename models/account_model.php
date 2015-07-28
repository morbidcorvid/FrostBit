<?php

Class Account_model extends CI_Model {

	// Insert registration data in database
	public function add($data) {
		// Query to insert data in database
		return $this->db->insert('accounts', $data);
	}
	
	// Read data using username and password
	public function validate($username,$password) {
		
		$condition = "user_name = '$username' AND user_pass = '$password'";
		$this->db->select('*');
		$this->db->from('accounts');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	// Read data from database to show data in admin page
	public function get_user($username) {
		
		$condition = "user_name = '$username'";
		$this->db->select('*');
		$this->db->from('accounts');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) 
		{
			return $query->row();
		} 
		else 
		{
			return false;
		}
	}

	public function get_role_accounts ($role)
	{
		$query = $this->db->get_where('accounts', array('role'=>$role));
		return $query;
	}
	
	public function get_accounts()
	{
		$sql = "	SELECT *
					FROM accounts
					WHERE	role != '' AND role != 'customer'
					GROUP BY last_name, first_name";
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function load_customer_tab ($file)
	{
		$this->db->trans_start();
		foreach ($file as $customer) {
			$customer = explode("\t",$customer);
			if (count($customer) > 1) {
				$data = array (
						'first_name' => $customer[1],
						'last_name' => $customer[2],
						'street_address' => $customer[4],
						'extra_line' => $customer[3],
						'post_office' => $customer[5],
						'zip_code' => $customer[6]
				);
				$this->db->insert('accounts', $data);
			}
		}
		$this->db->trans_complete();	
		return $this->db->trans_status();
	}
	
	public function get_user_cart($account_id)
	{
		$sql = "select 		cart_id as id, cart_action as action
				from 		cart_history
				where 		account_id = ?
							and cart_action like 'checked%'
				order by 	action_time DESC
				LIMIT 		1";
		$cart = $this->db->query($sql, array($account_id))->row();
		return $cart;
	}
}

?>