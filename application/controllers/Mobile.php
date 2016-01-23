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
		if (is_null($token)) {
			$token = $this->input->post('token', TRUE);
		}
		$this->login_check->mobile($token);
		// $this->login_check->check_init($required_power);
		$this->load->model(array('Msite', 'Mmission', 'Muser', 'Mwar             '));

	}
	
	public function index(){
		
		$this->load->view("template/map");
		// $this->load->view("site/control_panel",$data);
	}

	public function qr_parse(){
		$type = $this->input->post('type', TRUE);
		$resultData = array();
		// $resultData[0] = $type;
		$resultData[1] = false;

		if ($type == 1) { //site
			$qr_md5 = $this->input->post('site_md5', TRUE);
			$site_info = $this->Msite->qr_get_site_info($qr_md5);
			// get user info
			$user_info = $this->Muser->get_user_info($this->session->userdata('m_id'));
			
			if ($site_info['state']!=false&&$this->Msite->is_our_site($user_info['team'], $site_info['s_id'])) {
				//yes //是自己的點
				
				$result = $this->Msite->find_the_last_visit($site_info['s_id']);

				if ($result['state']==true&&$result['team']!=$user_info['team']) {
					//	there is a fight 加入戰爭
										// echo "123";
					// die("123");
					$resultData[1] = true;	//state
					$resultData[2] = 6; 	//Rid
				}else if($result['state']==true){


					//get resource
					$this->Msite->add_visit_record($user_info['id'], $site_info['s_id'], 0);
					$now = date('U');
					$deltaT = $now - strtotime($result['timestamp']);
					$resource = $deltaT*1;//<================================resource factor

					$this->Muser->add_resource($site_info['type'], $resource, $user_info['id']);

					$resultData[1] = true;	//state
					$resultData[2] = 1; 	//Rid
					$resultData[3] = $site_info['name']; // var 1
					$resultData[4] = $site_info['type']; // var 2
					$resultData[5] = $resource; 	//var 3 resource

				}

			}else if ($site_info['state']!=false&&$this->Msite->is_it_a_empty_site($site_info['s_id'])) {
				//yes
				$this->Msite->capture_Site($user_info['id'], $site_info['s_id']);
				$this->Msite->add_visit_record($user_info['id'], $site_info['s_id'], 0);
				// ========================================================================>沒檢查相鄰
				if (false) {
					$resultData[1] = true;	//state
					$resultData[2] = 3; 	//Rid
				}else{
					$resultData[1] = true;	//state
					$resultData[2] = 4; 	//Rid
					$resultData[3] = $site_info['name']; // var 1
					$resultData[4] = $site_info['type']; // var 2
				}

					


			}else if ($site_info['state']!=false) {
				//it is a site of opposite team
				if (false) {
					//有戰爭 不可加入
					$resultData[1] = true;	//state
					$resultData[2] = 2; 	//Rid
				}else{
					$war_id = $this->Mwar->start_new_war();
					$this->Msite->add_visit_record($user_info['id'], $site_info['s_id'], 1);
					//沒戰爭 發起戰爭
					$resultData[1] = true;	//state
					$resultData[2] = 5; 	//Rid
					$resultData[3] = $site_info['name']; // var 1
					$resultData[4] = $war_id; // var 1

				}
			}else{
				
			}

		}
		$data['json_data'] = $resultData;
		$this->load->view('template/jsonview', $data);
	}
	public function war_count_down(){
		$resultData[1] = false;
		$war_id = $this->input->post('war_id',TRUE);
		$user_id = $this->session->userdata('m_id');
		if ($this->Mwar->is_any_rescue($war_id, $user_id)) {
			//yes there's a rescue, go into war

			$resultData[1] = true;	//state
			$resultData[2] = 9; 	//Rid
			$resultData[3] = $site_info['name']; // var 1


		}else if($this->Mwar->is_over_180s($war_id, $user_id)){
			//yes over 180
			$site_info = $this->Mwar->get_war_site_info($war_id);
			if ($site_info['state']!=false) {
				$this->Mwar->set_str_user_win($war_id);
				$this->Msite->capture_Site($user_id, $site_info['s_id']);

				$resultData[1] = true;	//state
				$resultData[2] = 7; 	//Rid
				$resultData[3] = $site_info['name']; // var 1
			}
		}else{
			//no keep waiting
			$resultData[1] = true;	//state
			$resultData[2] = 8; 	//Rid
		}


		$data['json_data'] = $resultData;
		$this->load->view('template/jsonview', $data);
	}
}
?>