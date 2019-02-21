<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// header('Access-Control-Allow-Origin: *');

class ProductController extends CI_Controller {

    public function __construct(){

        parent::__construct();

        $this->desKey  = $this->config->config['des_key'];
    }

    public function index(){
        $data['heading'] = 'An Error Was Encountered';
        $data['message'] = 'no access';
        $this->load->view('errors/html/error_404',$data); 
    }

    public function readProduct(){
        
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

        $this->load->model('MProduct');

        $com = $this->MProduct->readProduct($dataReceive);
        echo json_encode($com);

        // debug($com);

    }

    public function addProduct(){

        $p_data             = $this->input->post('data');
        $dataReceive        = TripleDES::decryptText($p_data,$this->desKey);
        $dataReceive        = json_decode($dataReceive,true);

        if($dataReceive == ''){
            $arrRetrun = array( "sflag"=>false, "msg"=>"Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }

        ## check param
        $arrParam = array('barcode','productname','producttype','productunit');
        foreach ($arrParam as $key) {
            if(!isset($dataReceive[$key])){
                $arrRetrun = array( "sflag"=>false, "msg"=>"Parameter Error ".$key);
                echo json_encode($arrRetrun);
                return;
            }
        }
        ## --

        $this->load->model('MProduct');

        $com = $this->MProduct->addProduct($dataReceive);
        echo json_encode($com);
    }

    protected function insLogs($action,$data){
        $fp = fopen('request.log', 'a');

        $d['action'] = $action;
        $d['data']   = $data;

        fwrite($fp, print_r($d,true));
        
        fclose($fp);
    }


}//end class

