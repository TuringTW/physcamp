<?php
class Site extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('My_url_helper','url'));
		$this->load->library('session');
		$this->load->model(array('login_check','Msite'));
		// check login & power, and then init the header
		$required_power = 2;
		$this->login_check->check_init($required_power);

	}
	private function view_header(){
		$data = array(	'title' => 'Home', 
						'user' => $this->session->userdata('user'),
						'power' => $this->session->userdata('power')
					);
	}
	public function get_all_site()
	{
		$data['json_data'] = $this->Msite->get_all_site();
		$this->load->view('template/jsonview', $data);
	}

}
?>