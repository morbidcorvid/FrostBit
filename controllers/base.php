<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('account_model');
		$this->output->enable_profiler(FALSE);
	}
	
	public function load_view($page,$data=NULL)
	{
		$this->load->view('templates/header',$data);
		$this->load->view($page,$data);
		$this->load->view('templates/footer');
	}

	public function index($data=NULL)
	{
		$this->load_view('home',$data);
	}
	public function start_user_session($username)
	{
		$user = $this->account_model->get_user($username);
		$this->session->user_name = $user->user_name;
		$user_data = array(
				'id'=> $user->id,
				'user_name'=>$user->user_name,
				'first_name'=>$user->first_name,
				'last_name'=>$user->last_name,
				'role'=>$user->role,
				'email'=>$user->email,
		);
		$this->session->set_userdata($user_data);
	}
	public function login()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
		if ($this->form_validation->run()==FALSE)
		{
			$this->load_view('home',array('show_login'=>TRUE,'status'=>'Form Validation Failed'));
		}
		else
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			if($this->account_model->validate($username,$password) == FALSE)
			{
				$this->load_view('home',array('show_login'=>TRUE,'status'=>'Username or Password Invalid'));
			}
			else
			{
				$this->start_user_session($username);
				redirect('');
			}
		}
	}
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('');
	}
	
	public function register()
	{
		$this->load_view('register');
	}
	
	public function register_action()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('first_name', 'Username', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Password', 'trim|required');
		$this->form_validation->set_rules('user_name', 'Username', 'trim|required|is_unique[accounts.user_name]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('templates/header');
			$this->load->view('register');
			$this->load->view('templates/footer');
		}
		else
		{
			$registrant = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'user_name' => $this->input->post('user_name'),
				'user_pass' => $this->input->post('password'),
				'role' => 'customer',
				'email' => $this->input->post('first_name'),
				'street_address' => $this->input->post('street'),
				'extra_line'=> $this->input->post('street2'),
				'post_office' => $this->input->post('city_state'),
				'zip_code' => $this->input->post('zip')
			);
			$this->account_model->add($registrant);
			
			$this->start_user_session($registrant['user_name']);
			redirect('');
		}
	}
}