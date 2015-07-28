<?php
class Sale extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//redirect out if allowed role
		$allowed_roles = array('employee','admin');
		if(!(isset($this->session->userdata['role']) 
			&& in_array($this->session->userdata('role'), $allowed_roles))) 
		{
			$this->session->set_flashdata('warning','Not authorized to access Sales section!');
			redirect('');
		}
		
		//load models
		$this->load->model('account_model');
		$this->load->model('accounting_model');
		$this->load->model('order_model');
		$this->load->model('item_model');
		$this->load->model('cart_model');
		
		$this->output->enable_profiler(FALSE);
	}
	
	public function index()
	{		
		$id = $this->session->userdata['id'];
		$cart = $this->account_model->get_user_cart($id);
		if (sizeof($cart) && $cart->action == 'checked out')
		{
			$data['items'] = $this->cart_model->get_stocks($cart->id);
		}
		else
		{
			$data['items'] = $this->item_model->get_by_class('good');
		}
		$this->load->view('templates/header');
		$this->load->view('quick_sale', $data);
		$this->load->view('templates/footer');
	}
	
	public function place_order()
	{	
		$id = $this->session->userdata['id'];
		$cart = $this->account_model->get_user_cart($id);	
		$items = array();
		foreach($this->input->post(NULL,TRUE) as $key => $quantity) 
		{
			if ($quantity > 0)
			{
				$item_id = ltrim($key,'item_');
				$item = $this->item_model->get($item_id);
				$item->quantity = $quantity;
				array_push($items, $item);
				
				//if user is selling from cart, remove from cart stock
				if (sizeof($cart) && $cart->action == 'checked out')
				{
					$this->cart_model->update_stock($cart->id, $item_id, -$quantity);
				}
			}
		}
		$account = $this->account_model->get_user($this->session->userdata['user_name']);
		$details = array(
				'items' => $items,
				'account' => $account->id
		);
		
		$order_id = $this->order_model->quick_sale($details);
		
		$this->session->set_flashdata('success','The order was placed!');
		redirect('sale');		
	}
}
?>