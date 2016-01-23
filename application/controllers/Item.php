<?php
class Item extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('My_url_helper','url', 'My_sidebar_helper'));
		$this->load->library('session');
		$this->load->model(array('login_check','Msite', 'Mitem'));
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


	public function index(){
		$this->view_header();
		$data['item'] = $this->Mitem->get_all_item();
		$data['active'] = 0;
		$this->load->view("item/sidebar",$data);		
		$this->load->view("item/index/control_panel",$data);
	}
	public function itemlist(){
		$this->view_header();
		$data['itemlist'] = $this->Mitem->get_all_list();
		$data['active'] = 1;
		$this->load->view("item/sidebar",$data);		
		$this->load->view("item/list/control_panel",$data);
	}

}
?>