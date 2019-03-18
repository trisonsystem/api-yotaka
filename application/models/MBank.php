<?php
class MBank extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function search_bank( $aData ){
		$arrParam = array('hotel_id');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }
		$WHERE   = "";
		$sql 	= "	SELECT BK.*
					FROM m_bank AS BK
					WHERE 1 = 1 $WHERE AND status = '1'
					ORDER BY BK.id ";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		// debug($arr);
		return $arr;
	}

	public function search_bank_list( $aData ){
		$arrParam = array('hotel_id');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }
		$WHERE   = "";
		$WHERE  .= " AND BK.hotel_id='".$aData["hotel_id"]."'";
		$sql 	= "	SELECT BK.*, B.name_th, B.name_en
					FROM m_bank_number_list AS BK
					LEFT JOIN m_bank AS B ON BK.m_bank_id = B.id
					WHERE 1 = 1 $WHERE AND BK.status = '1'
					ORDER BY BK.id ";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		// debug($arr);
		return $arr;
	}

	
}
?>