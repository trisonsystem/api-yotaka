<?php
header('Access-Control-Allow-Origin: *');

class PaymentController extends CI_Controller {
    public $strUrl = "";
    public function __construct(){
        parent::__construct();

        $this->des_key  = $this->config->config['des_key'];
        $this->load->model('MPayment');

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

    public function search_payment(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MPayment->search_payment( $aData );
        print_r( json_encode($res) );
    }

    public function search_payment_status(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MPayment->search_payment_status( $aData );
        print_r( json_encode($res) );
    }

    public function search_payment_type(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MPayment->search_payment_type( $aData );
        print_r( json_encode($res) );
    }

    public function chang_status(){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res    = $this->MPayment->chang_status( $aData );
        print_r( json_encode($res) );
    }

    public function search_booking(){
        // echo "string";
        
        $aData = $this->Decode_TripleDES( $_POST );
        // debug($aData, true);
        $res   = $this->MPayment->search_booking( $aData );
        print_r( json_encode($res) );
    }
}