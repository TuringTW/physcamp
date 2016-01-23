

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Muser extends CI_Model
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
   
    function get_user_info($user_id){
        $this->db->select('id, name, user, team, level, exp, blood, str, dex, inte, money, wood, iron, ruby, note')->from('user')->where('id', $user_id);
        $query = $this->db->get();
        $result['state'] = 0;
        if ($query->num_rows()>0) {
            $result['result'] = $query->result_array()[0];      
            $result['state'] = 1;
        }
        
        return $result;
    }
    function add_resource($type, $value, $user_id){
        switch ($type) {
            case 0: //money
                $data=array('money'=>$value);
                break;
            case 1: //wood
                $data=array('wood'=>$value);
                break;
            case 2: //iron
                $data=array('iron'=>$value);
                break;
            case 3: //ruby
                $data=array('ruby'=>$value);
                break;
            default:
                $data = array();
                break;
        }

        $this->db->where('id', $user_id);
        $this->db->update('user', $data);
    }
    function minus_hp($user_info, $value){
        $data = array('blood'=>($user_info['blood']-$value>0)?$user_info['blood']-$value:0);
        $this->db->where('id', $user_info['id']);
        $this->db->update('user', $data);
    }
    
}

?>