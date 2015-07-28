<?php
class Carts extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//redirect out if allowed role
		$allowed_roles = array('employee','admin');
		if(!(isset($this->session->userdata['role']) 
			&& in_array($this->session->userdata('role'), $allowed_roles))) 
		{
			$this->session->set_flashdata('warning','Not authorized to access Carts section!');
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
		$data['has_cart'] = (sizeof($cart) && $cart->action == 'checked out' ? TRUE : FALSE);
		$data['user_cart'] = (sizeof($cart) ? $cart->id : 0);
		$data['carts'] = $this->cart_model->get_all();
		
		$this->load->view('templates/header');
		$this->load->view('manage_carts', $data);
		$this->load->view('templates/footer');
	}
	
	public function check_in($cart)
	{	
		$this->cart_model->check_in($cart, $this->session->userdata['id']);
		$this->session->set_flashdata('success','Cart checked in!');
		redirect('carts');
	}
	
	public function check_out($cart)
	{	
		$this->cart_model->check_out($cart, $this->session->userdata['id']);
		$this->session->set_flashdata('success','Cart checked out!');
		redirect('carts');
	}
	
	public function view($cart)
	{	
		$data['cart'] = $this->cart_model->get($cart);
		$data['items'] = $this->cart_model->get_stocks($cart);
		$this->load->view('templates/header');
		$this->load->view('cart', $data);
		$this->load->view('templates/footer');
	}
	
	public function stock($cart)
	{	
		$data['cart'] = $this->cart_model->get($cart);
		$data['items'] = $this->cart_model->get_all_stocks($cart);
		$this->load->view('templates/header');
		$this->load->view('stock_cart', $data);
		$this->load->view('templates/footer');
	}
	
	public function stock_action()
	{
		$cart_id = $this->input->post('cart_id');
		foreach($this->input->post(NULL,TRUE) as $key => $quantity) 
		{
			if ($key != "cart_id" && $quantity != 0)
			{
				$item_id = ltrim($key,'item_');
				$this->cart_model->update_stock($cart_id, $item_id, $quantity);
			}
		}
		$this->session->set_flashdata('success','Stocks updated!');
		redirect('carts');
	}
}
?>