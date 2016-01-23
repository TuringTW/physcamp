

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login_check extends CI_Model
{
     function __construct()
     {
          // Call the Model constructor
     	$this->load->database();
          parent::__construct();
     }

     //get the username & password from tbl_usrs
     function get_user($usr, $pwd)
     {
          $sql = "select `name`,`power`, `id` from `user` where `user` = '" . $usr . "' and pass = '" . md5($pwd) . "' and active = 1";
          $query = $this->db->query($sql);
          return $query->row();
     }

     function check_init($required_power){
     	$this->load->helper('My_url_helper');
		$this->load->helper('url');
		$this->load->library('session');

		// login check
		if ($this->session->userdata('user')===false) {
			redirect('/login');
		}
		// power check
		if ($this->session->userdata('power')===false) {
			redirect('/login');
		}else{
			if ($this->session->userdata('power') > $required_power) {
				redirect('/index');
			}
		}
		
    }
    function get_user_id(){
    	return $this->session->userdata('m_id');
    }
    function log_out($method=0){
  		$this->load->library('session');
  		$this->session->sess_destroy();
  		if ($method==0) {
        redirect('/login');
      }
		
		
    }
    function mobile($token){
      
      $this->db->select('name, user, power, id')->from('user')->where('token', $token)->where('active', 1);
      $result = $this->db->get();
      $temp = $result->result_array();
      if (count($temp) != 1) {
        redirect('/login');
        // print_r($temp);

      }else{
        $sessiondata = array(
            'user' => $temp[0]['name'],
            'power' => $temp[0]['power'],
            'm_id' => $temp[0]['id']
          );
        $this->load->library('session');
        $this->session->set_userdata($sessiondata);
      }
      
    }
}?>