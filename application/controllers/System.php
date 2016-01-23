<?php
class System extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('My_url_helper','url', 'My_sidebar_helper'));
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
		$this->load->view("template/header");
		$this->load->view("template/header_2", $data);
	}
	

	public function log(){
		$this->view_header();
		$data['site'] = $this->Msite->get_all_site();
		$data['active'] = 0;
		$this->load->view("system/sidebar",$data);
		
	}
	public function qrgen(){
		$this->view_header();
		$data['site'] = $this->Msite->get_all_site();
		$data['active'] = 1;
		$this->load->view("system/sidebar",$data);
		$this->load->view("system/qrgen/control_panel",$data);


	}

}
?>