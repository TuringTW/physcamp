

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
        $result = $query->result_array()[0];
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
    
}

?>