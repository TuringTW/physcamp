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
	public function mobile_login(){
		$user = $this->input->post('user', TRUE);
		$pass = $this->input->post('pass', TRUE);

		$result['json_data'][0] = false;

		if (!(is_null($user)||is_null($pass))) {

			$pass_encrypt = md5($pass);
			$this->db->select('user, name, id, power')->from('user')->where('user', $user)->where('pass', $pass_encrypt);
			$query = $this->db->get();
			$temp = $query->result_array();
			if (count($temp)==1) {
				$time = date('U');
				srand($time);
				$randnum = rand()*$time;
				$token = md5($randnum);
				$data = array('token'=>$token);
				$this->db->where('id', $temp[0]['id']);

				$this->db->update('user', $data);
				$result['json_data'][1] = $token;
				$result['json_data'][0] = TRUE;
			}
		}
		$this->load->view('template/jsonview', $result);
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
						'm_id' => $result->id
					);
				$this->session->set_userdata($sessiondata);
				// $this->load->view(print_r($this->session));

				redirect('/index');
			}else{
				redirect('/login?error=1');
			}
		}
	}
}
?>