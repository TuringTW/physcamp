<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Native Session Library
 *
 * @package     Sms
 * @subpackage  Libraries
 * @category    Sms
 * @author      Turing
 */

class Web
{

    public function __construct()
    {

    }
    public function send_sms($rx, $tx, $content, $note, $userdata)
    {
        if(!is_null($rx)&!is_null($content)){
            //$toURL = "127.0.0.1/dorm/test.php";
            $toURL = "http://www.sms-get.com/api_send.php";
            $data = array(
              "username" => $userdata["user"],
              "password" => $userdata["pass"],
              "method" => 1,
              "sms_msg" => "$content",
              "phone" => "$rx"
            );

            $response = $this->cURL($toURL,$data, 'post');
            $temp = json_decode($response,true);
            switch ($temp['error_code']) {
                case '000':
                    $errorcode = 0;
                    break;
                case '001':
                    $errorcode = 1;
                    break;
                case '002':
                    $errorcode = 2;
                    break;
                case '003':
                    $errorcode = 3;
                    break;
                case '004':
                    $errorcode = 4;
                    break;
                case '005':
                    $errorcode = 5;
                    break;
                case '006':
                    $errorcode = 6;
                    break;
                case '007':
                    $errorcode = 7;
                    break;
                case '008':
                    $errorcode = 8;
                    break;
                case '009':
                    $errorcode = 9;
                    break;
            }

            $errormsg = explode('|',$temp['error_msg']);
            
            

            $kevin =array('status' => $temp['stats']?"1":"0",'error_code'=>$errorcode,'cost'=>$errormsg[1],'last'=> $errormsg[2]);
            
        }else{
            $kevin =array('status' => 0);
            
        }
        return $kevin;
    }
    public function cURL($url, $post, $method)
    {
        $header=NULL;
        $cookie=NULL;
        //$user_agent = $_SERVER['HTTP_USER_AGENT']; 
        $user_agent = 'Mozilla/5.0 (Windows NT 5.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2';
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_HEADER, $header);
        // curl_setopt($ch, CURLOPT_NOBODY, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        // if ($method = 'post') {
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // }
        
        
        if ($post) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
          
        if($result){
            return $result;
        }else{
            return $error;
        }
    }
}
