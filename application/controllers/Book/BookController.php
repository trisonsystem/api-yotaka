<?php
header('Access-Control-Allow-Origin: *');

class BookController extends CI_Controller {
    public $strUrl = "";
    public function __construct(){
        parent::__construct();

        $this->des_key  = $this->config->config['des_key'];
        $this->load->model('MBook');

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

    public function search_room_forbook(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MBook->search_room_forbook( $aData );
        print_r( json_encode($res) );
    }
    
    public function search_customer(){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res     = $this->MCustomer->search_customer( $aData );
        print_r( json_encode($res) );
    }

    public function save_data(){
        $aData = $this->Decode_TripleDES( $_POST );
        $res   = $this->MBook->save_data( $aData );
        print_r( json_encode($res) );
    }

    public function search_status_book(){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res    = $this->MBook->search_status_book( $aData );
        print_r( json_encode($res) );
    }

    public function search_book_list(){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res    = $this->MBook->search_book_list( $aData );
        print_r( json_encode($res) );
    }

    public function chang_status(){
        $aData  = $this->Decode_TripleDES( $_POST );
        $res    = $this->MBook->chang_status( $aData );
        print_r( json_encode($res) );
    }
}


