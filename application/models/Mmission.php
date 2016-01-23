

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mmission extends CI_Model
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
        

        $query = $this->db->get();
        $result['sites'] = $query->result_array();
        return $result;

    }
    
    
}?>