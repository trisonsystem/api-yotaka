<?php
header('Access-Control-Allow-Origin: *');

class RoomController extends CI_Controller {
    public $strUrl = "";
    public function __construct(){
        parent::__construct();

        $this->des_key  = $this->config->config['des_key'];
        $this->load->model('MRoom');
       

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

    public function search_room(){
        $aData = $this->Decode_TripleDES( $_POST );
        $data  = $this->MRoom->search_room( $aData );
        print_r( json_encode($data) );
    }
    
    public function search_type_room(){
    	$aData = $this->Decode_TripleDES( $_POST );
        $data  = $this->MRoom->search_type_room( $aData );
        print_r( json_encode($data) );
    }

    public function search_room_item(){
        $aData = $this->Decode_TripleDES( $_POST );
        $data  = $this->MRoom->search_room_item( $aData );
        print_r( json_encode($data) );
    }

    public function search_room_item_list(){
        $aData = $this->Decode_TripleDES( $_POST );
        $data  = $this->MRoom->search_room_item_list( $aData );
        print_r( json_encode($data) );
    }

    public function save_data(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MRoom->save_data( $aData );
        print_r( json_encode($res) );
    }

    public function chang_status(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MRoom->chang_status( $aData );
        print_r( json_encode($res) );
    }

    public function search_room_forbook(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MRoom->search_room_forbook( $aData );
        print_r( json_encode($res) );
    }
}