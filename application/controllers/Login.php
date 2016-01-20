<?php
class Login extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'My_url_helper', 'security'));
		$this->load->library('session');
		$this->load->model('login_check');
		
		// session
		$this->session->sess_destroy();

	}

	public function index()
	{
		//get the posted values
		$username = $this->input->post("username");
		$password = $this->input->post("password");

		// validate
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() === FALSE){
			$this->load->view('template/login');

		}else if ($this->input->post('btnlogin')=='login') {

			$result = $this->login_check->get_user(xss_clean($username), $password);
			if (count($result)>0) {
				$sessiondata = array(
						'user' => $result->name,
						'power' => $result->power,
						'm_id' => $result->m_id
					);
				$this->session->set_userdata($sessiondata);
				// $this->load->view(print_r($this->session));

				redirect('/index');
			}else{
				redirect('/login');
			}
		}
	}
}
?>