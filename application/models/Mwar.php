

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msite extends CI_Model
{
     function __construct()
     {
          // Call the Model constructor
        $this->load->library('session');
        $this->load->model(array('login_check'));
        $required_power = 2;
        $this->login_check->check_init($required_power);
        parent::__construct();

    }
    // 取得合約列表

    function start_new_war($user_id, $site_id){ //0 for same team, 1 for diff team
        $data = array(
            's_user'=>$user_id,
            'site_id'=>$site_id
            ) ;    //0 for normal visit
        $this->db->insert('war_record', $data);
        return $this->db->insert_id();
    }
    function is_any_rescue($war_id, $user_id){
        $this->db->select('r_user')->from('war_record')->where('id', $war_id)->where('s_user', $user_id);
        $query = $this->db->get();
        $result = $query->result_array()[0]['r_user'];
        if (is_null($result)) {
            return false;
        }else{
            return true;
        }
    }
 
}?>