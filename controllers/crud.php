<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class Crud extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->database();
		$this->load->helper('url');
	
		$this->load->library('grocery_CRUD');
		
		if(!(isset($this->session->userdata['role']) && $this->session->userdata('role') == 'admin'))
		{
			$this->session->set_flashdata('warning','Not authorized to access Admin section!');
			redirect('');
		}
	}
	
	public function crud_output($output = null)
	{
		$this->load->view('templates/header');
		$this->load->view('crudOutput.php',$output);
		$this->load->view('templates/footer');
	}
	
	public function index()
	{
		$this->crud_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}
	
	public function accounts()
	{
		$output = $this->grocery_crud->render();
		$this->crud_output($output);    	
	}
	public function ledger_accounts()
	{
		$crud = new grocery_crud();
		$crud->set_table('ledger_accounts')
			->columns('id','account_name')
			->display_as('account_name','Account Name');
		$crud->fields('id','account_name');
		$output = $crud->render();
		$this->crud_output($output);
	}
	public function goods_services()
	{
		$crud = new grocery_crud();
		$crud->set_table('goods_services')
			->set_relation('ledger_in_id','ledger_accounts','account_name')
			->set_relation('ledger_out_id','ledger_accounts','account_name');
		$crud->set_field_upload('image','assets/uploads/files');
		$output = $crud->render();
		$this->crud_output($output);
	}
	public function orders()
	{
		$crud = new grocery_crud();
		$crud->set_table('orders')
			->set_relation('accounts_id','accounts','last_name');
		$output = $crud->render();
		$this->crud_output($output);
	}
	public function details()
	{
		$crud = new grocery_crud();
		$crud->set_table('details')
			->set_relation('order_id','orders','id')
			->set_relation('good_service_id','goods_services','short_description')
			->set_relation('ledger_accounts_id','ledger_accounts','account_name');
		$output = $crud->render();
		$this->crud_output($output);
	}
	public function carts()
	{
		$output = $this->grocery_crud->render();
		$this->crud_output($output);
	}
	public function cart_history()
	{
		$crud = new grocery_crud();
		$crud->set_table('cart_history')
			->set_relation('cart_id','carts','name')
			->set_relation('account_id','accounts','user_name');
		$output = $crud->render();
		$this->crud_output($output);
	}
	public function cart_stock()
	{
		$crud = new grocery_crud();
		$crud->set_table('cart_stock')
			->set_relation('cart_id','carts','name')
			->set_relation('gs_id','goods_services','short_description');
		$output = $crud->render();
		$this->crud_output($output);
	}
}