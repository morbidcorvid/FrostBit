<?php
class Accounting extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		//check that user has access
		if(!(isset($this->session->userdata['role']) && $this->session->userdata('role') == 'admin'))
		{
			$this->session->set_flashdata('warning','Not authorized to access Accounting section!');
			redirect('');
		}
		//load models
		$this->load->model('accounting_model');
		//profiler for debugging
		$this->output->enable_profiler(FALSE);
	}
	
	public function trialBalance()
	{
		$data['query'] = $this->accounting_model->get_trial_balance();
		//$response = $this->accounting_model->request_journals();
		
		$this->load->view('templates/header');
		$this->load->view('output_query', $data);
		//$this->load->view('ledger_engine_response', $response);
		$this->load->view('templates/footer');
	}
	
	public function journal($date = null)
	{
		$date = date_create($date);
		
		$selectData['selectedDate'] = date_format($date,"Y-m-d");
		$selectData['query'] = $this->accounting_model->get_order_dates();
		
		$journal_entry = $this->accounting_model->get_journal_entry(date_format($date,"Y-m-d"));
		$data['query'] = $journal_entry;
		
		$this->load->view('templates/header');
		$this->load->view('order_date_select', $selectData);
		$this->load->view('output_query', $data);
		if ($journal_entry->num_rows() > 0)
		{
			$jvData = $this->accounting_model->post_journal_entry($journal_entry, $date);
			$this->load->view('ledger_engine_response', $jvData);
		}
		$this->load->view('templates/footer');
	}
	
	public function auditTrail()
	{
		$data['query'] = $this->accounting_model->get_audit_trail();
		
		$this->load->view('templates/header');
		$this->load->view('output_query', $data);
		$this->load->view('templates/footer');
	}
	
	public function jvTest()
	{
		$ledgers = array(
			1000 => 0,
			3000 => 0
		);
		
		$date = date("YmdHis");
		
		$data = $this->accounting_model->send_jv($ledgers, $date);
		
		$this->load->view('templates/header');
		$this->load->view('output_jv', $data);
		$this->load->view('templates/footer');
	}
}
?>