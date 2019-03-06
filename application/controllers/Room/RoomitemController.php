<?php
header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Origin: *');

class RoomitemController extends CI_Controller
{
	public $strUrl = "";
    public function __construct()
    {
        parent::__construct();

        $this->des_key  = $this->config->config['des_key'];
        $this->load->model('MRoomitem');
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

    public function search_roomitem( $aData = "" ){
		$aData  = $this->Decode_TripleDES( $_POST );
        $res     = $this->MRoomitem->search_roomitem( $aData );
        print_r( json_encode($res) );
    }

    public function save_data(){
    	$aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MRoomitem->save_data( $aData );
        print_r( json_encode($res) );
    }

    public function chang_status(){
    	$aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MRoomitem->chang_status( $aData );
        print_r( json_encode($res) );
    }
}