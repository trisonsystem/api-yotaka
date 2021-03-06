<?php
header('Access-Control-Allow-Origin: *');

class BankController extends CI_Controller {
    public $strUrl = "";
    public function __construct(){
        parent::__construct();

        $this->des_key  = $this->config->config['des_key'];
        $this->load->model('MBank');

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

    public function search_bank(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MBank->search_bank( $aData );
        print_r( json_encode($res) );
    }

    public function search_bank_list(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MBank->search_bank_list( $aData );
        print_r( json_encode($res) );
    }

    public function search_banknumberlist( $aData = "" ){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res     = $this->MBank->search_banknumberlist( $aData );
        print_r( json_encode($res) );
    }

    public function save_data(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MBank->save_data( $aData );
        print_r( json_encode($res) );
    }

    public function chang_status(){
        $aData = $this->Decode_TripleDES( $_POST );        
        $res   = $this->MBank->chang_status( $aData );
        print_r( json_encode($res) );
    }
}
?>