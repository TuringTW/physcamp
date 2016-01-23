<?php
class Mobile extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('My_url_helper','url', 'My_sidebar_helper'));
		$this->load->library('session');
		$this->load->model(array('login_check'));
		// check login & power, and then init the header
		$required_power = 2;
		$token = $this->input->get('token', TRUE);
		$this->login_check->mobile($token);
		$this->load->model(array('Msite', 'Mmission'));

	}
	
	public function get_all_site()
	{
		$data['json_data'] = $this->Mmission->get_mission_site();
		$this->load->view('template/jsonview', $data);
	}

	public function index(){
		
		$this->load->view("template/map");
		// $this->load->view("site/control_panel",$data);
	}

}
?>