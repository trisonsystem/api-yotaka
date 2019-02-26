<?php
header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Origin: *');

class MpositionController extends CI_Controller
{
	public $strUrl = "";
    public function __construct()
    {
        parent::__construct();

        $this->des_key  = $this->config->config['des_key'];
        $this->load->model('MMposition');
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

    public function search_position( $aData = "" ){
		$aData  = $this->Decode_TripleDES( $_POST );
        $res     = $this->MMposition->search_position( $aData );
        print_r( json_encode($res) );
    }
}