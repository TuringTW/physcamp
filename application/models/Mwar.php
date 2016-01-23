

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
    function is_over_180s($war_id, $user_id){
        $this->db->select('UNIX_TIMESTAMP(strtimestamp)')->from('war_record')->where('id', $war_id)->where('s_user', $user_id);
        $query = $this->db->get();
        $result = $query->result_array()[0]['strtimestamp'];
        $now = date('U');

        if ($now-$result>180) {
            return true;
        }else{
            return false;
        }
    }
    function get_war_site_info($war_id){
        $this->db->select()

        $this->db->select('name, site.id as s_id, type')->from('war_record')
        $this->db->join('site', 'site.id=war_record.site_id', 'left');
        $this->db->where('war_record.id', $war_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $result['state'] = true;
        if ($query->num_rows()>1) {
                        
        }else if ($query->num_rows()==1) {
            $result = $result[0];
            $result['state'] = true;
        }else{
            $result['state'] = false;
        }
        // print_r($result);
        // die();
        return $result;
    }
    function set_str_user_win($war_id){
        $data=array('win'=>1);
        $this->db->where('id', $war_id);
        $this->db->update('war_record',$data);
    }
 
}?>