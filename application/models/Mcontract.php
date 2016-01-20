

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mcontract extends CI_Model
{
     function __construct()
     {
          // Call the Model constructor
        $this->load->library('session');
        $this->load->model(array('login_check', 'Mutility'));
        $required_power = 2;
        $this->login_check->check_init($required_power);
        parent::__construct();

    }
    // 取得合約列表
    function show_contract_list($keyword, $dorm, $seal, $due, $outofdate, $page, $order_rule=0, $page_rule=0)
    {
        $this->db->select('contract.contract_id,contract.rent,contract.sales,student.name as sname,dorm.name as dname,room.name as rname,  contract.s_date,contract.in_date,contract.out_date ,  contract.e_date, contract.c_date, COUNT(contract.contract_id) as countp, seal, student.name as sname, mobile')->from('contract');
        $this->db->join('contractpeo','contractpeo.contract_id=contract.contract_id','left');
        $this->db->join('room','room.room_id=contract.room_id','left');
        $this->db->join('dorm','room.dorm=dorm.dorm_id','left');
        $this->db->join('student','student.stu_id=contractpeo.stu_id','left');
        $this->db->where('( 0',NULL, false); //for logic 
        $this->db->or_like('dorm.name',$keyword)->or_like('room.name',$keyword)->or_like('student.name',$keyword)->or_like('mobile',$keyword);
        $this->db->or_where('0 )',NULL, false);
        $this->db->where('seal',0);

        // 逾期
        if ($outofdate==1) {
            $ofdrule = "DATEDIFF(`e_date`,'".date('Y-m-d')."')<=0";
            $this->db->where($ofdrule);
        }
        // 即將到期
        if ($due==1) {
            $duerule = "(Month(`e_date`)=".date('m')." and Year(`e_date`)=".date('Y').")";
            $this->db->where($duerule);
        }
        //一個月內遷出
        if ($due==2) {
            $duerule = "(DATEDIFF(`out_date`, '".date("Y-m-d")."')<30 and DATEDIFF(`out_date`, '".date("Y-m-d")."')>=0)";
            $this->db->where($duerule);
        }

        // 宿舍
        if ($dorm != 0&&!is_null($dorm)) {
            $dormrule = "`dorm`.`dorm_id` = '$dorm'";
            $this->db->where($dormrule);
        }
        $this->db->group_by('contract.contract_id');
        // 排序規則
        if ($order_rule==0) {
            $this->db->order_by("dorm.name", "desc"); 
            $this->db->order_by("room.name", "desc"); 
            $this->db->order_by("in_date", "desc"); 
        }

        if ($order_rule = 1) {
            $this->db->order_by("out_date");
            $this->db->order_by("dorm.name", "desc"); 
            $this->db->order_by("room.name", "desc"); 
        }
         if ($order_rule = 2) {
            $this->db->order_by("e_date");
            $this->db->order_by("dorm.name", "desc"); 
            $this->db->order_by("room.name", "desc"); 
        }

        // 頁數
        if ($page_rule == 0) {        
            if ($page <= 0) {
                $page = 1;
            }
            $pages = 30*$page-30;
            $this->db->limit(30,$pages);
        }



        $query = $this->db->get();
        return $query->result_array();

    }
    // 取得單筆合約資料
    function get_contract_info($contract_id){
        if (!is_nan($contract_id)) {
            $this->db->select('dorm.name as dname, dorm.dorm_id, room.name as rname, room.room_id, student.name as sname, student.stu_id, contract.contract_id, s_date, e_date, in_date, out_date, student.id_num, c_date, contract.note, contract.rent, contract.sales, manager.name as mname, student.mobile');
            $this->db->from('contract');
            $this->db->join('contractpeo','contractpeo.contract_id=contract.contract_id','left');
            $this->db->join('room','room.room_id=contract.room_id','left');
            $this->db->join('dorm','room.dorm=dorm.dorm_id','left');
            $this->db->join('manager','manager.m_id=contract.manager','left');
            $this->db->join('student','student.stu_id=contractpeo.stu_id','left');    
            $this->db->where('contract.contract_id', $contract_id)->where('seal<>', 1); 

            $query = $this->db->get();
            return $query->result_array();
        }else{
            return false;
        }
    }
    function edit_contract($contract_id, $in_date, $out_date, $sales, $note){
        $sql = "UPDATE `contract` set   `in_date` = '$in_date',
                                        `out_date` = '$out_date',
                                        `sales` = '$sales',
                                        `note` = '$note'
                                where `contract_id` = '$contract_id'";
        $query = $this->db->query($sql);
        // return $query->affected_rows();
        return true;
    }
    function break_contract($contract_id, $b_date){
        if (!is_null($contract_id)&&!is_null($b_date)) {
            $m_id = $this->session->userdata('m_id');
            $time = Date('Y-m-d h:i:s');

            $sql = "UPDATE `contract` set `e_date` = '$b_date', `note` = CONCAT(`note`,'bc at $time by $m_id,') 
                    where `contract_id` = '$contract_id'";
            $query = $this->db->query($sql);
            return true;
        }else{
            return false;
        }
    }
    function count_ofd_due($dorm, $keyword){
        $duerule = "(Month(`e_date`)=".date('m')." and Year(`e_date`)=".date('Y').")";
        

        $this->db->distinct()->select('contract.contract_id')->from('contract');    
        // join
        $this->db->join('contractpeo','contractpeo.contract_id=contract.contract_id','left');
        $this->db->join('room','room.room_id = contract.room_id','left');
        $this->db->join('dorm','room.dorm=dorm.dorm_id','left');
        $this->db->join('student','student.stu_id=contractpeo.stu_id','left');
        //where 
        $this->db->where('( 0',NULL, false); //for logic 
        $this->db->or_like('dorm.name',$keyword)->or_like('room.name',$keyword)->or_like('student.name',$keyword)->or_like('mobile',$keyword);
        $this->db->or_where('0 )',NULL, false);
        $this->db->where('seal',0);
        // 逾期
        $ofdrule = "DATEDIFF(`e_date`,'".date('Y-m-d')."')<=0";
        $this->db->where($ofdrule);
        // 宿舍
        if ($dorm != 0&&!is_null($dorm)) {
            $dormrule = "`dorm`.`dorm_id` = '$dorm'";
            $this->db->where($dormrule);
        }
        $result['countofd'] = $this->db->count_all_results();


    // 本月到期
        $this->db->distinct()->select('contract.contract_id')->from('contract');    
        // join
        $this->db->join('contractpeo','contractpeo.contract_id=contract.contract_id','left');
        $this->db->join('room','room.room_id = contract.room_id','left');
        $this->db->join('dorm','room.dorm=dorm.dorm_id','left');
        $this->db->join('student','student.stu_id=contractpeo.stu_id','left');
        //where 
        $this->db->where('( 0',NULL, false); //for logic 
        $this->db->or_like('dorm.name',$keyword)->or_like('room.name',$keyword)->or_like('student.name',$keyword)->or_like('mobile',$keyword);
        $this->db->or_where('0 )',NULL, false);
        $this->db->where('seal',0);
        // 本月到期
        $duerule = "(Month(`e_date`)=".date('m')." and Year(`e_date`)=".date('Y').")";
        $this->db->where($duerule);
        // 宿舍
        if ($dorm != 0&&!is_null($dorm)) {
            $dormrule = "`dorm`.`dorm_id` = '$dorm'";
            $this->db->where($dormrule);
        }
        $result['countdue'] = $this->db->count_all_results();
    //一個月內到期
        $this->db->distinct()->select('contract.contract_id')->from('contract');    
        // join
        $this->db->join('contractpeo','contractpeo.contract_id=contract.contract_id','left');
        $this->db->join('room','room.room_id = contract.room_id','left');
        $this->db->join('dorm','room.dorm=dorm.dorm_id','left');
        $this->db->join('student','student.stu_id=contractpeo.stu_id','left');
        //where 
        $this->db->where('( 0',NULL, false); //for logic 
        $this->db->or_like('dorm.name',$keyword)->or_like('room.name',$keyword)->or_like('student.name',$keyword)->or_like('mobile',$keyword);
        $this->db->or_where('0 )',NULL, false);
        $this->db->where('seal',0);
        // 本月到期
        $duerule = "(DATEDIFF(`out_date`, '".date("Y-m-d")."')<30 and DATEDIFF(`out_date`, '".date("Y-m-d")."')>=0)";
        $this->db->where($duerule);
        // 宿舍
        if ($dorm != 0&&!is_null($dorm)) {
            $dormrule = "`dorm`.`dorm_id` = '$dorm'";
            $this->db->where($dormrule);
        }
        $result['countdue_in_1_m'] = $this->db->count_all_results();
        return $result;
    }
// 這個不太好
    function date_check_by_room($room_id, $in_date, $out_date, $contract_id){
        $this->db->select('contract_id, room_id');
        $this->db->from('contract');
        // join

        $this->db->where("((DATEDIFF('$in_date', in_date)>=0 and DATEDIFF(out_date, '$in_date')>=0 )
            or    (DATEDIFF('$in_date', in_date)<=0 and DATEDIFF(out_date, '$out_date')<=0) 
            or    (DATEDIFF('$out_date', in_date)>=0 and DATEDIFF(out_date, '$out_date')>=0) )and seal<>1");
        $this->db->where('contract.room_id', $room_id);

        $query = $this->db->get();
        $result = $query->result_array();

        if (($query->num_rows() == 0||($query->num_rows()==1&&$result[0]['contract_id']==$contract_id))&&(strtotime($out_date)-strtotime($in_date)>0)) {
            return true;
        }else{
            return false;
        }
    }
    function set_check_out($contract_id){
        if (!is_null($contract_id)&&is_numeric($contract_id)) {
            $m_id = $this->session->userdata('m_id');
            $time = date('Y-m-d h:i:s');
            $sql = "UPDATE `contract` set `seal` = 2, note = CONCAT(`note`, 'check out at $time by $m_id,') where `contract_id` = '$contract_id'";
            $query = $this->db->query($sql);
            return true;
        }else{
            return false;
        }
    }

    function checknotoverlap($room_id, $start, $end){
         $sql = "SELECT  `dorm`.`name` as `dname`, `room`.`name` as `rname`, `student`.`name` as `sname`, `student`.`mobile`,  `contract`.`s_date`,`contract`.`in_date`,`contract`.`out_date` ,  `contract`.`e_date`, COUNT(`contractpeo`.`stu_id`) as `countp`
                    from `contract` 
                    LEFT JOIN `contractpeo` on `contract`.`contract_id` = `contractpeo`.`contract_id`
                    LEFT join `room` on `room`.`room_id`=`contract`.`room_id`
                    LEFT JOIN `dorm` on `dorm`.`dorm_id`=`room`.`dorm`
                    LEFT JOIN `student` on `student`.`stu_id`=`contractpeo`.`stu_id`
                             where `contract`.`seal`=0 and `room`.`room_id`= '$room_id' and
                            ((DATEDIFF(  `contract`.`in_date`,'$start' ) >=0
                            AND DATEDIFF(   `contract`.`out_date`,'$end' ) <=0) or 
                            (DATEDIFF(  `contract`.`out_date`,'$start' ) >=0
                            AND DATEDIFF(   `contract`.`out_date`,'$end' ) <=0) or 
                            (DATEDIFF(  `contract`.`out_date`,'$start' ) >=0
                            AND DATEDIFF(   `contract`.`in_date`,'$start' ) <=0) or 
                            (DATEDIFF(  `contract`.`out_date`,'$end' ) >=0
                            AND DATEDIFF(   `contract`.`in_date`,'$end' ) <=0)) 
                    GROUP BY `contractpeo`.`stu_id`" ; 
        $query = $this->db->query($sql);
        $output = array();
        if ($query->num_rows()>0) {
            $output['state'] = false;
            $output['result'] = $query->result_array();
        }else{
            $output['state'] = true;
        }

        return $output;
    }
    function add_contract($data){
        $c_date = date('Y-m-d h:i:s');
        $manager = $this->login_check->get_user_id();


        $result = array();
        $result['error_id'] = array();
        $result['state'] = 1;
        $insertdata = array(    'room_id'=>$data['room_id'],  
                                's_date'=>$data['s_date'],  
                                'e_date'=>$data['e_date'],
                                'c_date'=>$c_date,
                                'in_date'=>$data['in_date'],
                                'out_date'=>$data['out_date'],
                                'manager'=>$manager,
                                'rent'=>$data['rent'],
                                'sales'=>$data['sales'],
                                'note'=>$data['note']);
        $this->db->insert('contract', $insertdata);
        $contract_id = $this->db->insert_id();
        if ($this->db->affected_rows()>0) {
            $insertdata = array();
            for ($i=0; $i < count($data['stu_id']); $i++) { 
                $insertdatum = array('contract_id'=> $contract_id, 'stu_id'=>$data['stu_id'][$i]);
                array_push($insertdata, $insertdatum);
            }

            $this->db->insert_batch('contractpeo', $insertdata);
            
            if ( $this->db->affected_rows()>0) {
                $rent = $this->Mfinance->rent_cal($data['rent'], $data['s_date'], $data['e_date'], count($data['stu_id']));
                $output = $this->Mfinance->add_rent_record(1, $rent['rent_result']['total_rent'], date('Y-m-d'), '房屋/房間租金總額', $contract_id);
                $result['state'] = $output['state'];
                $result['contract_id'] = $contract_id;
                
            }else{
                $result['state'] = -1;
            }
        }else{
            $result['state'] = 0;
        }
            
        return $result;
        
            
    }
    function get_print_data($contract_id){
        $result = array();
        $this->db->select('dorm.name as dname, room.name as rname, student.name as sname, mobile, birthday, reg_address, mailing_address, id_num, home, emg_name, emg_phone, s_date, e_date, in_date, out_date, contract.rent, location');
        $this->db->from('contract');
        $this->db->join('contractpeo','contractpeo.contract_id=contract.contract_id','left');
        $this->db->join('room','room.room_id = contract.room_id','left');
        $this->db->join('dorm','room.dorm=dorm.dorm_id','left');
        $this->db->join('student','student.stu_id=contractpeo.stu_id','left');
        $this->db->where('contract.contract_id=', $contract_id)->where('seal<>', 1);
        $this->db->order_by('student.name')->order_by('student.mobile');
        $query = $this->db->get();
        $countpeo = $query->num_rows();
        $result['countpeo'] = $countpeo;
        $result['data'] = $query->result_array();
        $datum = $result['data'][0];
        $result['rent'] = $this->Mfinance->rent_cal($datum['rent'], $datum['s_date'], $datum['e_date'], $countpeo);
        $result['countday'] = $this->Mutility->Date_diff($datum['s_date'], $datum['e_date']);
        
        $this->db->flush_cache();
        $this->db->select('*')->from('rent')->where('contract_id', $contract_id);
        $query = $this->db->get();
        $result['rent_list'] = $query->result_array();
        return $result;
    }
    function get_keep_info($contract_id){

        $this->db->select('contract.room_id, dorm as dorm_id, e_date, out_date, stu_id, room.rent')->from('contract');
        $this->db->join('contractpeo','contractpeo.contract_id=contract.contract_id','left');
        $this->db->join('room','room.room_id = contract.room_id','left');
        $this->db->where('contract.contract_id=', $contract_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function show_avail_room($dorm_id, $str_date, $end_date, $lprice, $hprice, $type){
        if (is_null($str_date)||empty($str_date)) {
            $str_date = date('Y-m-d');
        }
        if (is_null($end_date)||empty($end_date)) {
            $end_date = (new DateTime($str_date))-> modify('+1 day') -> format('Y-m-d');
        }

        $this->db->select('dorm.name as dname, room.name as rname, room.type, if(isnull(precontract.contract_id), "",precontract.contract_id) as pre_id, if(isnull(precontract.out_date), "",precontract.out_date) as out_date, if(isnull(postcontract.contract_id), "", postcontract.contract_id)  as post_id, if(isnull(postcontract.in_date),"",postcontract.in_date) as in_date, room.rent, room.room_id, if(isnull(postcontract.postmin), 4000, postcontract.postmin) as postmin, if(isnull(precontract.premin), 4000, precontract.premin) as premin');
        $this->db->from('room');
        // join
        $this->db->join('dorm', 'dorm.dorm_id = room.dorm', 'left');
        $this->db->join("(select temp.* from (select contract_id, room_id, (DATEDIFF('$str_date',out_date )) as premin, out_date from contract where DATEDIFF('$str_date',in_date )>0 and DATEDIFF('$str_date',out_date )>0 and seal<>1 order by room_id, DATEDIFF('$str_date',out_date ) ) as temp group by `room_id`) as precontract ", 'precontract.room_id = room.room_id', 'left');

        $this->db->join("(select temp1.* from (select contract_id, room_id, (DATEDIFF(in_date, '$end_date')) as postmin, in_date from contract where DATEDIFF(in_date, '$end_date')>0 and DATEDIFF(out_date, '$end_date')>0 and seal<>1 order by room_id, DATEDIFF(out_date, '$end_date') ) as temp1 group by room_id) as postcontract", 'postcontract.room_id = room.room_id', 'left');
        $this->db->join("(select count(contract_id) as countc, room_id from contract where (     
                    (DATEDIFF('$str_date', in_date)>=0 and DATEDIFF(out_date, '$str_date')>=0 )
            or    (DATEDIFF('$str_date', in_date)<=0 and DATEDIFF(out_date, '$end_date')<=0) 
            or    (DATEDIFF('$end_date', in_date)>=0 and DATEDIFF(out_date, '$end_date')>=0) )and seal<>1 group by room_id) as contractcheck", 'contractcheck.room_id = room.room_id', 'left');
        $this->db->where('isnull(`contractcheck`.`countc`)', 1);
        
        if ($type<>0) {
            $this->db->where('room.type', $type);
        }
        if ($dorm_id<>0) {
            $this->db->where('dorm.dorm_id=', $dorm_id);
        }
        $this->db->where('rent>=', $lprice)->where('rent<=', $hprice);
        $this->db->order_by('premin');
        $this->db->order_by('postmin');
        
        
        $this->db->order_by('dorm.name', 'desc');
        $this->db->order_by('room.name', 'desc');
        $this->db->limit(200, 0);
        $query = $this->db->get();
        return $query->result_array();
    }
    function move_to_new_data_base(){
        $link = mysqli_connect('127.0.0.1','client','1qaz2wsx','dorm2');
        mysqli_query($link,"SET NAMES 'UTF8'");

        $sql = "SELECT distinct `c_num`, `room_id`, `s_date`, `e_date`, `c_date`, `in_date`, `out_date`, `rent`, `payed_rent`, `timestamp`, `seal`, `sales`, `keep`, `bank_id`, `return`, `manager`, `note` from `contract` where 1 group by `c_num`";
        $result = mysqli_query($link, $sql);

        $contract = array();
        while($row = mysqli_fetch_assoc($result)){
            array_push($contract, $row);
        }

        foreach ($contract as $key => $value) {
        
            $this->db->insert('contract', $value);
            $c_num = $value['c_num'];
            $contract_id = $this->db->insert_id();
            $sql = "SELECT `stu_id` from `contract` where `c_num` = '$c_num'";
            $result = mysqli_query($link, $sql);

            $stulist = array();
            while($row = mysqli_fetch_assoc($result)){
                $row['contract_id'] = $contract_id;
                array_push($stulist, $row);
            }

            $this->db->insert_batch('contractpeo', $stulist);

        }




        return 1;
    }
    function pdf_gen($contract_id, $method){
        $this->load->library(array('pdf'));
        
        if (!is_null($contract_id)&&is_numeric($contract_id)) {
            $data = $this->Mcontract->get_print_data($contract_id);
            
            $this->pdf->SetAuthor('AunttsaiDormSYS');
            $this->pdf->SetTitle('蔡阿姨宿舍租賃合約');
            $this->pdf->SetSubject('蔡阿姨宿舍租賃合約');
            $this->pdf->SetKeywords('租賃,合約');
            $this->pdf->SetHeaderMargin(0);
            $this->pdf->SetTopMargin(5);
            $this->pdf->setFooterMargin(0);
            $this->pdf->SetAutoPageBreak(true);
            $this->pdf->SetDisplayMode('real', 'default');

            
            

            $pw = $this->pdf->getPageWidth()*2.5;  
            $data['wu'] = $pw;
            $data['barcodetext'] = date('Y-m-d').'-'.$contract_id;
            // add a page

            $this->pdf->AddPage();
            // $this->pdf->SetFont('msungstdlight', '', 12);
            $this->pdf->load_view('contract/pdf/index', $data);
            ob_end_clean();
            if ($method == 0) {
                //client side
                $this->pdf->Output('My-File-Name.pdf', 'I');
                return 0;
            }else if($method == 1){
                //server side
                $root = $_SERVER['SCRIPT_FILENAME'];
                $root = mb_substr($root,0,strpos($root,'index.php'));

                $this->pdf->Output($root.'/contract_pdf/contract_'.$contract_id.'.pdf', 'F');
                $path = $root.'/contract_pdf/contract_'.$contract_id.'.pdf';
                return $path;
            }
            
        }else{
            return false;
        }
    }
    
}?>