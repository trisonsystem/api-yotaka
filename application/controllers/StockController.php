<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// header('Access-Control-Allow-Origin: *');

class StockController extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->desKey  = $this->config->config['des_key'];
    }

    public function index(){
       
        $data['heading'] = 'An Error Was Encountered';
        $data['message'] = 'no access';
        $this->load->view('errors/html/error_404',$data); 
    }

    public function readStock(){

        $p_data             = $this->input->post('data');
        $dataReceive        = TripleDES::decryptText($p_data,$this->desKey);
        $dataReceive        = json_decode($dataReceive,true);

        if($dataReceive == ''){
            $arrRetrun = array( "sflag"=>false, "msg"=>"Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }

        ## check param
        $arrParam = array('page','limit');
        foreach ($arrParam as $key) {
            if(!isset($dataReceive[$key])){
                $arrRetrun = array( "sflag"=>false, "msg"=>"Parameter Error ".$key);
                echo json_encode($arrRetrun);
                return;
            }
        }
        ## --

        $this->load->model('MStock');

        $dataReturn = $this->MStock->readStock($dataReceive);
        echo json_encode($dataReturn);
    }

    public function testapi(){
        
        $p_data = $this->input->post('data');

        // debug($p_data,true);

        $p_data             = base64_decode($p_data);
        $dataReceive        = TripleDES::decryptText($p_data,'KsAsFUHSyl9bH3qUTxxHg1mZGRgwQpQ4');
        $dataReceive        = json_decode($dataReceive,true);

        if($dataReceive == ''){
            $arrRetrun = array( "sflag"=>false, "msg"=>"Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }

        // $dataReceive['ipRemote'] = (isset($_SERVER['HTTP_CF_CONNECTING_IP']))? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
        debug($dataReceive);

        ## check param
        // $arrParam = array('username','status','page','agent','date','web');
        // foreach ($arrParam as $key) {
        //     if(!isset($dataReceive[$key])){
        //         $arrRetrun = array( "sflag"=>false, "msg"=>"Parameter Error ".$key);
        //         echo json_encode($arrRetrun);
        //         return;
        //     }
        // }
        ## --

        $this->load->model('MMaster');

        $com = $this->MMaster->autocProduct();
        
        // echo json_encode($com);

        debug($com);

    }

    protected function insLogs($action,$data){
        $fp = fopen('request.log', 'a');

        $d['action'] = $action;
        $d['data']   = $data;

        fwrite($fp, print_r($d,true));
        
        fclose($fp);
    }


}//end class

