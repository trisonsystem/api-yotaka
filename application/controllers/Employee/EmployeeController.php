<?php
header('Access-Control-Allow-Origin: *');

class EmployeeController extends CI_Controller {
    public $strUrl = "";
    public function __construct(){
        parent::__construct();

        $this->des_key  = $this->config->config['des_key'];
        $this->load->model('MEmployee');
        $this->load->model('MMaster');

    }

    public function search_employee(){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res     = $this->MEmployee->search_employee( $aData );
        print_r( json_encode($res) );
    }

    public function search_division( $aData = "" ){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res     = $this->MMaster->search_division( $aData );
        print_r( json_encode($res) );
    }

    public function search_department( $aData = "" ){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res    = $this->MMaster->search_department( $aData );
        print_r( json_encode($res) );
    }

    public function search_position( $aData = "" ){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res    = $this->MMaster->search_position( $aData );
        print_r( json_encode($res) );
    }

    public function search_status_employee( $aData = "" ){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res    = $this->MEmployee->search_status_employee( $aData );
        print_r( json_encode($res) );
    }

    public function save_data(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MEmployee->save_data( $aData );
        print_r( json_encode($res) );
    }

    public function chang_status(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MEmployee->chang_status( $aData );
        print_r( json_encode($res) );
    }

    public function Decode_TripleDES( $aData ){
        $data = (isset($aData["data"])) ? $aData["data"] : $aData;
        $dataReceive = TripleDES::decryptText($data, $this->des_key);
        $dataReceive = json_decode($dataReceive, true);

        if ($dataReceive == '') {
            $arrRetrun = array("sflag" => false, "msg" => "Key TripleDES Error ");
            echo json_encode($arrRetrun);
            return;
        }
        return $dataReceive;
    }

}