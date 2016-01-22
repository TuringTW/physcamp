<?php
class Index extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('My_url_helper','url', 'My_sidebar_helper'));
		$this->load->library('session');
		$this->load->model(array('login_check'));
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
	public function index()
	{
		$this->view_header();
		$data['active'] = 0;
		$this->load->view("index/sidebar", $data);
		$this->load->view("index/control_panel");
		
		$this->load->view("index/js_section");

		$this->load->view("template/footer");
	}

	



// 登出
	public function logout()
	{
		$this->login_check->log_out();
	}
}
?>