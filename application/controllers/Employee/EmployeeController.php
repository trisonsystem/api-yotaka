<?php
header('Access-Control-Allow-Origin: *');

class EmployeeController extends CI_Controller {
    public $strUrl = "";
    public function __construct(){
        parent::__construct();

        $this->keyword  = $this->config->config['keyword'];
        $this->api_url  = $this->config->config['api_url'];
        $this->des_key  = $this->config->config['des_key'];
        $this->load->model('MEmployee');
        $this->load->model('MMaster');

    }

    public function search_employee(){
        $pd = $this->MEmployee->search_employee( $_GET );
        print_r( json_encode($pd) );
    }

    public function search_division( $aData = "" ){
        $aData    = ( isset($_GET['division_id']) ) ? $_GET : $aData ;
        $arr_data = $this->MMaster->search_division( $aData );
        return $arr_data;
    }

    public function search_department( $aData = "" ){
        $aData    = ( isset($_GET['department_id']) ) ? $_GET : $aData ;
        $arr_data = $this->MMaster->search_department( $aData );
        return $arr_data;
    }

    public function search_position( $aData = "" ){
        $aData    = ( isset($_GET['position_id']) ) ? $_GET : $aData ;
        $arr_data = $this->MMaster->search_position( $aData );
        return $arr_data;
    }

    public function search_status_employee( $aData = "" ){
        $aData    = ( isset($_GET['status_employee_id']) ) ? $_GET : $aData ;
        $arr_data = $this->MMaster->search_status_employee( $aData );
        return $arr_data;
    }

    public function save_data(){
        $res = $this->MEmployee->save_data( $_POST );
        print_r( json_encode($res) );
    }

    public function chang_status(){
        $res = $this->MEmployee->chang_status( $_POST );
        print_r( json_encode($res) );
    }
}