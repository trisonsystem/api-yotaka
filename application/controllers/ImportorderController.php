<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// header('Access-Control-Allow-Origin: *');

class ImportorderController extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->desKey  = $this->config->config['des_key'];
    }

    public function index(){
       
        $data['heading'] = 'An Error Was Encountered';
        $data['message'] = 'no access';
        $this->load->view('errors/html/error_404',$data); 
    }

    public function runbill(){
    	$p_data             = $this->input->post('data');
        
        $dataReceive        = TripleDES::decryptText($p_data,$this->desKey);
        $dataReceive        = json_decode($dataReceive,true);

        $this->load->model('MImportorder');

        $dataReturn = $this->MImportorder->runbill($dataReceive);
        echo json_encode($dataReturn);
    }

    public function saveImportOrder(){
        $p_data             = $this->input->post('data');
        
        $dataReceive        = TripleDES::decryptText($p_data,$this->desKey);
        $dataReceive        = json_decode($dataReceive,true);

        if($dataReceive == ''){
            $arrRetrun = array( "sflag"=>false, "msg"=>"Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }

        ## check param
        $arrParam = array('header','list');
        foreach ($arrParam as $key) {
            if(!isset($dataReceive[$key])){
                $arrRetrun = array( "sflag"=>false, "msg"=>"Parameter Error ".$key);
                echo json_encode($arrRetrun);
                return;
            }
        }
        ## --

        $this->load->model('MImportorder');

        $com = $this->MImportorder->saveImportOrder($dataReceive);
        echo json_encode($com);
    }

    public function readImportorder(){

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

        $this->load->model('MImportorder');

        $dataReturn = $this->MImportorder->readImportorder($dataReceive);
        echo json_encode($dataReturn);
    }

}