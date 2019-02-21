<?php
header('Access-Control-Allow-Origin: *');

class LanguageController extends CI_Controller {
    public function __construct(){
        parent::__construct();

        $this->des_key  = $this->config->config['des_key'];
    }

    public function getLang( $aData = "" ){ 
        $this->load->model('MLanguage');
        $aData  = $this->Decode_TripleDES( $_POST );
        $res     = $this->MLanguage->getLang( $aData );
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