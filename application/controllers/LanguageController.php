<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// header('Access-Control-Allow-Origin: *');

class LanguageController extends CI_Controller {
    public $strUrl = "";
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $data['heading'] = 'An Error Was Encountered';
        $data['message'] = 'no access';
        $this->load->view('errors/html/error_404',$data); 
    }

    public function show_index(){
        echo "API V0.0.1";
    }

    public function infoLanguage(){
        $g_data = $this->input->get();

        $g_data             = base64_decode($g_data);
        $dataReceive        = TripleDES::decryptText($g_data,'KsAsFUHSyl9bH3qUTxxHg1mZGRgwQpQ4');
        $dataReceive        = json_decode($dataReceive,true);

        if($dataReceive == ''){
            $arrRetrun = array( "sflag"=>false, "msg"=>"Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }

        $this->load->model('MLanguage');
        $res = $this->MLanguage->infoLanguage();
        
        echo json_encode($res);
    }
}