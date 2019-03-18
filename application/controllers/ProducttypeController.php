<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// header('Access-Control-Allow-Origin: *');

class ProducttypeController extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->desKey  = $this->config->config['des_key'];
    }

    public function index(){
       
        $data['heading'] = 'An Error Was Encountered';
        $data['message'] = 'no access';
        $this->load->view('errors/html/error_404',$data); 
    }

    public function readProducttype(){

        $p_data             = $this->input->post('data');
        // print_r($p_data);exit();
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

        $this->load->model('MProducttype');

        $dataReturn = $this->MProducttype->readProducttype($dataReceive);
        echo json_encode($dataReturn);
    }

    public function saveProducttype(){

        $p_data             = $this->input->post('data');
        $dataReceive        = TripleDES::decryptText($p_data,$this->desKey);
        $dataReceive        = json_decode($dataReceive,true);

        if($dataReceive == ''){
            $arrRetrun = array( "sflag"=>false, "msg"=>"Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }

        ## check param
        $arrParam = array('producttypeid','producttypename');
        foreach ($arrParam as $key) {
            if(!isset($dataReceive[$key])){
                $arrRetrun = array( "sflag"=>false, "msg"=>"Parameter Error ".$key);
                echo json_encode($arrRetrun);
                return;
            }
        }
        ## --

        $this->load->model('MProducttype');

        $com = $this->MProducttype->saveProducttype($dataReceive);
        echo json_encode($com);
    }
}