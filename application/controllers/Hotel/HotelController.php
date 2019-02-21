<?php
header('Access-Control-Allow-Origin: *');

class HotelController extends CI_Controller {
    public $strUrl = "";
    public function __construct(){
        parent::__construct();

        // $this->keyword  = $this->config->config['keyword'];
        // $this->api_url  = $this->config->config['api_url'];
        $this->des_key  = $this->config->config['des_key'];
        $this->load->model('MMaster');
        $this->load->model('MHotel');
    }

    public function search_hotel(){
        $this->load->model("MHotel");
        $aData = $this->Decode_TripleDES( $_POST );
    	$data  = $this->MHotel->search_hotel( $aData );
        print_r( json_encode($data) );
    }

     public function search_quarter(){
        $aData = $this->Decode_TripleDES( $_POST );
        $data  = $this->MMaster->search_quarter( $aData );
        print_r( json_encode($data) );
    }


    public function search_province( $aData = "" ){
        $aData = $this->Decode_TripleDES( $_POST );
        $data  = $this->MMaster->search_province( $aData );
        print_r( json_encode($data) );
    }

    public function search_amphur( $aData = "" ){
        $aData = $this->Decode_TripleDES( $_POST );
        $data  = $this->MMaster->search_amphur( $aData );
        print_r( json_encode($data) );
    }


     public function search_district( $aData = "" ){
        $aData = $this->Decode_TripleDES( $_POST );
        $data  = $this->MMaster->search_district( $aData );
        print_r( json_encode($data) );
    }


    public function search_status_hotel( $aData = "" ){
        $aData = $this->Decode_TripleDES( $_POST );
        $data  = $this->MHotel->search_status_hotel( $aData );
        print_r( json_encode($data) );
    }

    public function save_data(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MHotel->save_data( $aData );
        print_r( json_encode($res) );
    }

    public function chang_status(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MHotel->chang_status( $aData );
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