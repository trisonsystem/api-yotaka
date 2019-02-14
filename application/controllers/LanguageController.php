<?php
defined('BASEPATH') or exit('No direct script access allowed');
// header('Access-Control-Allow-Origin: *');

class LanguageController extends CI_Controller
{
    public $strUrl = "";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('MLanguage');
    }

    public function index()
    {
        $data['heading'] = 'An Error Was Encountered';
        $data['message'] = 'no access';
        $this->load->view('errors/html/error_404', $data);
    }

    public function show_index()
    {
        echo "API V0.0.1";
    }

    public function infoLanguage()
    {
        $g_data = $this->input->get();

        $dataReceive = TripleDES::decryptText($g_data['data'], 'KsAsFUHSyl9bH3qUTxxHg1mZGRgwQpQ4');
        $dataReceive = json_decode($dataReceive, true);

        if ($dataReceive == '') {
            $arrRetrun = array("sflag" => false, "msg" => "Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }

        $this->load->model('MLanguage');
        $ress = $this->MLanguage->getField();

        foreach ($ress as $key => $value) {
            if ($key != 0) {
                $resA[$key-1]['Field'] = $value['Field'];
            }            
        }

        $res['title'] = $resA;
        $res['list'] = $this->MLanguage->infoLanguage();
        
        echo json_encode($res);
    }

    public function getFieldTB(){
        $g_data = $this->input->get();

        $dataReceive = TripleDES::decryptText($g_data['data'], 'KsAsFUHSyl9bH3qUTxxHg1mZGRgwQpQ4');
        $dataReceive = json_decode($dataReceive, true);

        if ($dataReceive == '') {
            $arrRetrun = array("sflag" => false, "msg" => "Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }

        $this->load->model('MLanguage');
        $res = $this->MLanguage->getField();

        foreach ($res as $key => $value) {
            if ($key != 0) {
                $resA[$key-1]['Field'] = $value['Field'];
            }            
        }

        echo json_encode($resA);
    }

    public function saveLanguage()
    {
        $post = $this->input->post();

        $dataReceive = TripleDES::decryptText($post['data'], 'KsAsFUHSyl9bH3qUTxxHg1mZGRgwQpQ4');
        $dataReceive = json_decode($dataReceive, true);

        if ($dataReceive == '') {
            $arrRetrun = array("sflag" => false, "msg" => "Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }
        
        $res = array(
            'status' => 500,
            'msg' => '',
            'data' => ''
        );

        if (isset($dataReceive)) {
            $result = $this->MLanguage->SaveLanguage($dataReceive);
            if ($result == true) {
                $res['status'] = 200;
                $res['msg'] = 'Success';
                $res['data'] = $result;  
            } else {
                $res['msg'] = 'Error data not found';
            }         
            
        } else {
            $res['msg'] = 'error : 500';
            $res['data'] = '';
        }

        echo json_encode($res);

    }

    public function saveFieldLang(){
        $post = $this->input->post();

        $dataReceive = TripleDES::decryptText($post['data'], 'KsAsFUHSyl9bH3qUTxxHg1mZGRgwQpQ4');
        $dataReceive = json_decode($dataReceive, true);

        if ($dataReceive == '') {
            $arrRetrun = array("sflag" => false, "msg" => "Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }
        
        $res = array(
            'status' => 500,
            'msg' => '',
            'data' => ''
        );

        if (isset($dataReceive)) {
            $result = $this->MLanguage->SaveFieldLang($dataReceive);
            
            if ($result == true) {
                $res['status'] = 200;
                $res['msg'] = 'Success';
                $res['data'] = $result;  
            } else {
                $res['msg'] = 'Error data not found';
            }         
            
        } else {
            $res['msg'] = 'error : 500';
            $res['data'] = '';
        }

        echo json_encode($res);

    }

    public function deleteLanguage(){
        $post = $this->input->post();
        
        $dataReceive = TripleDES::decryptText($post['data'], 'KsAsFUHSyl9bH3qUTxxHg1mZGRgwQpQ4');
        $dataReceive = json_decode($dataReceive, true);

        if ($dataReceive == '') {
            $arrRetrun = array("sflag" => false, "msg" => "Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }
        
        $res = array(
            'status' => 500,
            'msg' => '',
            'data' => ''
        );
        
        if (isset($dataReceive)) {
            $result = $this->MLanguage->DeleteLanguage($dataReceive);
            
            if ($result == true) {
                $res['status'] = 200;
                $res['msg'] = 'Success';
                $res['data'] = $result;  
            } else {
                $res['msg'] = 'Error data not found';
            }         
            
        } else {
            $res['msg'] = 'error : 500';
            $res['data'] = '';
        }

        echo json_encode($res);
    }

    public function deleteFieldLang(){
        $post = $this->input->post();
        
        $dataReceive = TripleDES::decryptText($post['data'], 'KsAsFUHSyl9bH3qUTxxHg1mZGRgwQpQ4');
        $dataReceive = json_decode($dataReceive, true);

        if ($dataReceive == '') {
            $arrRetrun = array("sflag" => false, "msg" => "Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }
        
        $res = array(
            'status' => 500,
            'msg' => '',
            'data' => ''
        );
        
        if (isset($dataReceive)) {
            $result = $this->MLanguage->DeleteFieldLang($dataReceive);
            
            if ($result == true) {
                $res['status'] = 200;
                $res['msg'] = 'Success';
                $res['data'] = $result;  
            } else {
                $res['msg'] = 'Error data not found';
            }         
            
        } else {
            $res['msg'] = 'error : 500';
            $res['data'] = '';
        }

        echo json_encode($res);
    }
    

    public function saveEditLanguage(){
        $post = $this->input->post();

        $dataReceive = TripleDES::decryptText($post['data'], 'KsAsFUHSyl9bH3qUTxxHg1mZGRgwQpQ4');
        $dataReceive = json_decode($dataReceive, true);

        if ($dataReceive == '') {
            $arrRetrun = array("sflag" => false, "msg" => "Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }
        
        $res = array(
            'status' => 500,
            'msg' => '',
            'data' => ''
        );
        // debug($dataReceive, true);
        if (isset($dataReceive)) {
            $result = $this->MLanguage->SaveEditLanguage($dataReceive);
            // debug($result, true);
            if ($result == true) {
                $res['status'] = 200;
                $res['msg'] = 'Success';
                $res['data'] = $result;  
            } else {
                $res['msg'] = 'Error data not found';
            }         
            
        } else {
            $res['msg'] = 'error : 500';
            $res['data'] = '';
        }

        echo json_encode($res);
    }
}