<?php
class Mission extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('My_url_helper','url', 'My_sidebar_helper'));
		$this->load->library('session');
		$this->load->model(array('login_check','Msite', 'Mmission'));
		// check login & power, and then init the header
		$required_power = 2;
		$this->login_check->check_init($required_power);

	}
	private function view_header(){
		$data = array(	'title' => 'Home', 
						'user' => $this->session->userdata('user'),
						'power' => $this->session->userdata('power')
					);
		$this->load->view("template/header");
		$this->load->view("template/header_2", $data);
	}
	public function get_all_site()
	{
		$data['json_data'] = $this->Mmission->get_mission_site();
		$this->load->view('template/jsonview', $data);
	}

	public function index(){
		$this->view_header();
		// $data['site'] = $this->Msite->get_all_site();
		$data['active'] = 0;
		$this->load->view("site/sidebar",$data);
		// $this->load->view("site/control_panel",$data);
	}

}
?>