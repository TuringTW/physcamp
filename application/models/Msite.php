

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
    function get_all_site()
    {
        $this->db->select('site.id, site.name as sname, lati, longi, owner, type, IF(ISNULL(user.team), 0, user.team) as team')->from('site');
        $this->db->join('user','user.id=site.owner','left');
        $this->db->order_by('site.name');

        $query = $this->db->get();
        $result['sites'] = $query->result_array();
        return $result;

    }
    function qr_get_site_info($md5_code){
        $this->db->select('name, site.id as s_id, type')->from('site')->where('md5(id)', $md5_code);
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
    function is_our_site($team, $site_id){

        $this->db->from('site');
        $this->db->join('user', 'user.id=site.owner', 'left');
        $this->db->where('site.id', $site_id)->where('user.team', $team);
        $count = $this->db->count_all_results();
        if ($count == 1) {
            return TRUE;
        }else{
            return FALSE;
        }
    }
    function find_the_last_visit($site_id){
        $this->db->select('user.team, user.id as u_id, timestamp')->from('visit')->join('user', 'visit.user_id=user.id', 'left');
        $this->db->where('site_id', $site_id);
        $this->db->order_by('timestamp', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();

        if (count($result)>0) {
            $result = $result[0];
            $result['state'] = true;
        }else{
            $result['state'] = false;
        }
        return $result;

    }
    function add_visit_record($user_id, $site_id, $type){ //0 for same team, 1 for diff team
        $data = array(
            'user_id'=>$user_id,
            'site_id'=>$site_id, 
            'type'=>$type) ;    //0 for normal visit
        $this->db->insert('visit', $data);

    }
    function is_it_a_empty_site($site_id){
        $this->db->select('owner')->from('site')->where('id', $site_id);
        $query = $this->db->get();
        $result = $query->result_array()[0]['owner'];
        if ($result==0) {
            return true;
        }else{
            return false;
        }

    }
    function capture_Site($user_id, $site_id){
        $data = array('owner'=>$user_id);
        $this->db->where('id', $site_id);
        $this->db->update('site', $data);
    }
    
}?>