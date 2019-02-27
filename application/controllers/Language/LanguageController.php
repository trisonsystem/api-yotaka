<?php
header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Origin: *');

class LanguageController extends CI_Controller
{
    public $strUrl = "";
    public function __construct()
    {
        parent::__construct();

        $this->des_key  = $this->config->config['des_key'];
        $this->load->model('MLanguage');
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

    public function search_language( $aData = "" ){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res     = $this->MLanguage->search_language( $aData );
        print_r( json_encode($res) );
    }

     public function getLang( $aData = "" ){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res     = $this->MLanguage->getLang( $aData );
        print_r( json_encode($res) );
    }
      
}