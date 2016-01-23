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
		$this->load->model(array('Msite', 'Mmission', 'Muser', 'Mwar'));

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
			
			if ($site_info['state']!=false&&$user_info['state']!=false&&$this->Msite->is_our_site($user_info['result']['team'], $site_info['s_id'])) {
				//yes //是自己的點
				
				$result = $this->Msite->find_the_last_visit($site_info['s_id']);

				if ($result['state']==true&&$result['team']!=$user_info['result']['team']) {
					//	there is a fight 加入戰爭
										// echo "123";
					// die("123");

					$war_result = $this->Mwar->find_war_of_site($site_info['s_id']);
					if ($war_result['state']==1) {
						$resultData[1] = true;	//state
						$resultData[2] = 6; 	//Rid
						$resultData[3] = $site_info['name'];
						$resultData[4] = $war_result['result'][0]['id']; // war_id
						//登記對抗
						$this->Mwar->register_res($war_result['result'][0]['id'], $user_info['result']['id']);
					}
					// print_r($war_result);

				}else if($result['state']==true){


					//get resource
					$this->Msite->add_visit_record($user_info['result']['id'], $site_info['s_id'], 0);
					$now = date('U');
					$deltaT = $now - strtotime($result['timestamp']);
					$resource = $deltaT*1;//<================================resource factor

					$this->Muser->add_resource($site_info['type'], $resource, $user_info['result']['id']);

					$resultData[1] = true;	//state
					$resultData[2] = 1; 	//Rid
					$resultData[3] = $site_info['name']; // var 1
					$resultData[4] = $site_info['type']; // var 2
					$resultData[5] = $resource; 	//var 3 resource

				}

			}else if ($site_info['state']!=false&&$this->Msite->is_it_a_empty_site($site_info['s_id'])) {
				//yes
				$this->Msite->capture_Site($user_info['result']['id'], $site_info['s_id']);
				$this->Msite->add_visit_record($user_info['result']['id'], $site_info['s_id'], 0);
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
					$war_id = $this->Mwar->start_new_war($user_info['result']['id'], $site_info['s_id']);
					$this->Msite->add_visit_record($user_info['result']['id'], $site_info['s_id'], 1);
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
		// echo "test1";

		$resultData[1] = false;
		$war_id = $this->input->post('war_id',TRUE);
		$user_id = $this->session->userdata('m_id');
		$site_info = $this->Mwar->get_war_site_info($war_id);
		if ($this->Mwar->is_any_rescue($war_id, $user_id)) {

			//yes there's a rescue, go into war
			$resultData[1] = true;	//state
			$resultData[2] = 9; 	//Rid
			$resultData[3] = $site_info['name']; // var 1
			//========================================================================<缺什麼?

		}else if($this->Mwar->is_over_180s($war_id, $user_id)){
			//yes over 180
						// echo "test2";

			if ($site_info['state']!=false) {
				$this->Mwar->set_str_user_win($war_id);
				$this->Msite->capture_Site($user_id, $site_info['s_id']);

				$resultData[1] = true;	//state
				$resultData[2] = 7; 	//Rid
				$resultData[3] = $site_info['name']; // var 1
			}
		}else{
			//no keep waiting
						// echo "test3";

			$resultData[1] = true;	//state
			$resultData[2] = 8; 	//Rid
		}
		// echo "test2";
		// print_r($resultData);
		$data['json_data'] = $resultData;
		$this->load->view('template/jsonview', $data);
	}

	public function war_fight_record(){
		$resultData[1] = false;

		$war_id = $this->input->post('war_id', TRUE);
		$totalatx = $this->input->post('totalatx', TRUE);
		$find_result = $this->Mwar->find_user_str_res($war_id);


		$r_user_info = $this->Muser->get_user_info($find_result['result']['r_user']);
		$s_user_info = $this->Muser->get_user_info($find_result['result']['s_user']);
		$user_id = $this->session->userdata('m_id');
		$site_info = $this->Mwar->get_war_site_info($war_id);
		if ($r_user_info['state']==1&$s_user_info['state']==1) {
			if ($s_user_info['result']['id']==$user_id) {
				//是發起者
				$type = 1;
				$this->Mwar->add_fight_record($war_id, $totalatx, 1);
			}else {
				$type = 2;
				//不適
				$this->Mwar->add_fight_record($war_id, $totalatx, 2);
			}

			if ($this->Mwar->is_finish($war_id)) {
				// yes
				$result = $this->Mwar->calculate_winner($war_id, $s_user_info['result'], $r_user_info['result']);
				
				if ($result==1) {
					$this->Msite->capture_Site($s_user_info['result']['id'], $site_info['s_id']);
					if ($user_id == $s_user_info['result']['id']) {
						$resultData[1] = true;	//state
						$resultData[2] = 10; 	//Rid
					}else{
						$resultData[1] = true;	//state
						$resultData[2] = 11; 	//Rid
					}
						
				}else{
					$this->Msite->capture_Site($r_user_info['result']['id'], $site_info['s_id']);
					if ($user_id == $r_user_info['result']['id']) {
						$resultData[1] = true;	//state
						$resultData[2] = 10; 	//Rid
					}else{
						$resultData[1] = true;	//state
						$resultData[2] = 11; 	//Rid
					}
				}
			}else{
				// not finished yet
				$resultData[1] = true;	//state
				$resultData[2] = 12; 	//Rid
				
			}
		}
			

		$resultData[3] = $site_info['name']; // var 1

		$data['json_data'] = $resultData;
		$this->load->view('template/jsonview', $data);
	}
	
}
?>