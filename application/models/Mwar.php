

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mwar extends CI_Model
{
     function __construct()
     {
          // Call the Model constructor
        $this->load->library('session');
        $this->load->model(array('login_check', 'Muser', 'Msite'));
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
        $this->db->select('ISNULL(r_user) as r_state')->from('war_record')->where('id', $war_id)->where('s_user', $user_id);
        $query = $this->db->get();
        $result = 0;
        if ($query->num_rows()>0) {
            $result = $query->result_array()[0]['r_state'];
        }

        // print_r($result);
        if ($result==1) {
            return 0;
        }else{
            return 1;
        }
    }
    function register_res($war_id, $user_id){
        $data=array('r_user'=>$user_id);
        $this->db->where('id', $war_id);
        $this->db->update('war_record', $data);
    }
    function is_over_180s($war_id, $user_id){
        $this->db->select('UNIX_TIMESTAMP(strtimestamp) as strtimestamp')->from('war_record')->where('id', $war_id)->where('s_user', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $result = $query->result_array()[0]['strtimestamp'];
            // print_r($query->result_array());
        }else{
            $result = date('U');
        }
        
        $now = date('U');

        if ($now-$result>180) {
            return true;
        }else{
            return false;
        }
    }
    function get_war_site_info($war_id){
        $this->db->select('name, site.id as s_id, type')->from('war_record');
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
    function find_user_str_res($war_id){
        $this->db->select("s_user, r_user");
        $this->db->from('war_record')->where('id', $war_id);
        $query = $this->db->get();
        $result['state'] = 0;
        if ($query->num_rows()>0) {
            $result['result'] = $query->result_array()[0];      
            $result['state'] = 1;
        }
        
        return $result;
    }
    function add_fight_record($war_id, $totalatx, $type){// type 1 str 2 rec
        if ($type == 1) {
            $data = array(  'id'=>$war_id,
                            'str_attach_para'=>$totalatx,
                            'str_finish'=>1);
        }else{
            $data = array(  'id'=>$war_id,
                            'res_attach_para'=>$totalatx,
                            'res_finish'=>1);
        }
        $this->db->where('id', $war_id);
        $this->db->update('war_record', $data);
    } 
    function is_finish($war_id){
        $this->db->select('str_finish, res_finish')->from('war_record')->where('id', $war_id);
        $query = $this->db->get();
        $result = $query->result_array()[0];
        if ($result['str_finish']*$result['res_finish']==1) {
            return true;
        }else{
            return false;
        }
    }
    function calculate_winner($war_id, $s_user, $r_user){
        $time = date('U');
        srand($time);
        $s_rand_accuracy = rand(0, 100);
        $is_s_escape = ($s_user['dex']>$s_rand_accuracy);
        $r_rand_accuracy = rand(0, 100);
        $is_r_escape = ($r_user['dex']>$r_rand_accuracy);

        if ($is_s_escape&!$is_r_escape) {

            $data = array('str_loss_hp'=>0, 'res_loss_hp'=>$r_user['blood'], 'win'=>1);
            $this->db->where('id', $war_id);
            $this->db->update('war_record', $data);
            $this->Muser->minus_hp($r_user, $r_user['blood']);

        }else if(!$is_s_escape&$is_r_escape){

            $data = array('str_loss_hp'=>$s_user['blood'], 'res_loss_hp'=>0, 'win'=>0);
            $this->db->where('id', $war_id);
            $this->db->update('war_record', $data);
            $this->Muser->minus_hp($s_user, $s_user['blood']);

        }else{



        }

        return 1;
    }

    function find_war_of_site($site_id){
        $now = date('U');
        $this->db->select('id')->from('war_record')->where('ISNULL(r_user)', 1)->where('ISNULL(win)', 1)->where('str_finish', 0)->where('res_finish', 0)->where('site_id', $site_id)->where('(UNIX_TIMESTAMP(strtimestamp)+180)>', $now);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        $result['result'] = $query->result_array();

        if (count($result['result'])>0) {
            $result['state']=1;
        }else{
            $result['state']=0;
        }

        return $result;



    }
 
}?>