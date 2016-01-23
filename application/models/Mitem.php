

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mitem extends CI_Model
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
    function get_all_item()
    {
        $this->db->select('item.id, itemlist.id, item.user_id, user.team as team, user.name as uname, itemlist.name as iname, itemlist.type as itype')->from('item');
        $this->db->join('itemlist', 'item.item_id = itemlist.id', 'left');
        $this->db->join('user', 'user.id = item.user_id', 'left');
        $this->db->order_by('user.team')->order_by('user.name');
        $query = $this->db->get();
        $result['items'] = $query->result_array();
        return $result;
    }
    function get_all_list()
    {
        $this->db->select('id, name, type, atk')->from('itemlist');
        $query = $this->db->get();
        $result['itemlist'] = $query->result_array();
        return $result;
    }
    
    
}?>