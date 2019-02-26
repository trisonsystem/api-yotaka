<?php
class MRoom extends CI_Model {

	public function __construct(){
		parent::__construct();

	}
	
	public function search_room( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  = ( !isset( $aData["room_id"] ) ) ? "" : " AND R.id='".$aData["room_id"]."'";
			$WHERE  .= " AND R.hotel_id='".$aData["hotel_id"]."'";
		}
		$sql 	= " SELECT  R.* , TR.name AS type_room_name
					FROM m_room AS R 
					LEFT JOIN m_type_room AS TR ON R.m_type_room_id = TR.id
					WHERE 1 = 1  $WHERE ORDER BY R.id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_type_room( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  = ( !isset( $aData["type_room_id"] ) ) ? "" : " AND id='".$aData["type_room_id"]."'";
			$WHERE  .= " AND hotel_id='".$aData["hotel_id"]."'";
		}
		$sql 	= " SELECT  * FROM m_type_room WHERE 1 = 1  $WHERE ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}
}