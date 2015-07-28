<?php
class Admin extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//redirect out if not admin	
		if(!(isset($this->session->userdata['role']) && $this->session->userdata('role') == 'admin')) 
		{
			$this->session->set_flashdata('warning','Not authorized to access Admin section!');
			redirect('');
		}
		
		//load models
		$this->load->model('account_model');
		$this->load->model('accounting_model');
		$this->load->model('order_model');
		$this->load->model('item_model');
		
		$this->output->enable_profiler(FALSE);
	}
	
	public function order()
	{
		
		$data['items'] = $this->item_model->get_by_class('good');
		$this->load->view('templates/header');
		$this->load->view('item_grid', $data);
		$this->load->view('templates/footer');
	}
	
	public function order_item($item)
	{
		$data['item'] = $this->item_model->get($item);
		$data['suppliers'] = $this->account_model->get_role_accounts('supplier');
		$data['payments'] = $this->item_model->get_by_class('finance');
		
		$this->load->view('templates/header');
		$this->load->view('order_item', $data);
		$this->load->view('templates/footer');
	}
	
	public function generic_order()
	{
		$data['items'] = $this->item_model->get_all();
		$data['accounts'] = $this->account_model->get_accounts();
		$data['payments'] = $this->item_model->get_by_class('finance');
		
		$this->load->view('templates/header');
		$this->load->view('generic_order', $data);
		$this->load->view('templates/footer');
	}
	
	
	public function order_action()
	{
		$this->load->model('order_model');
		$details = array(
				'item' => $this->input->post('item'),
				'quantity' => $this->input->post('quantity'),
				'payment' => $this->input->post('payment'),
				'account' => $this->input->post('account'),
				'cost' => $this->input->post('cost')
		);
		$order_id = $this->order_model->order_item($details);
		
		$data['query'] = $this->accounting_model->get_audit_trail($order_id);
		
		$this->load->view('templates/header');
		$this->load->view('output_query', $data);
		$this->load->view('templates/footer');
	}
	
	public function upload_customers()
	{
		
		$file = file("20030916CustomerList.tab",1);
		
		if ($this->account_model->load_customer_tab($file) === FALSE)
		{
			// generate an error... or use the log_message() function to log your error
			echo "<h3> There was an error inserting, rolled back...</h3>";
		}
		else {
			echo "It's all good.";
		}
		
	}
}
?>