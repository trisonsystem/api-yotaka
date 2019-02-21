<?php
class MMaster extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function autocProduct(){

		$keysearch 	= $this->input->get('term');
		$arr 		= array();

		$sqlAuto	= "select * from product where name like '%".$keysearch."%' limit 50";
		$queryAuto 	= $this->db->query($sqlAuto);
		$checkAuto  = $queryAuto->num_rows();

        if($checkAuto > 0){
			foreach ($queryAuto->result_array() as $key => $value) {

				$arrVal['label'] 	= $value['name'];
				$arrVal['value'] 	= $value;
				$arr[] 				= $arrVal;
			}
		}

		return $arr;
	}
	public function autocDistributor(){

		$keysearch 	= $this->input->get('term');
		$arr 		= array();

		$sqlAuto	= "select * from distributor where name like '%".$keysearch."%' limit 50";
		$queryAuto 	= $this->db->query($sqlAuto);
		$checkAuto  = $queryAuto->num_rows();

        if($checkAuto > 0){
			foreach ($queryAuto->result_array() as $key => $value) {

				$arrVal['label'] 	= $value['name'];
				$arrVal['value'] 	= $value;
				$arr[] 				= $arrVal;
			}
		}

		return $arr;
	}

	public function search_division( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  = ( !isset($aData["division_id"]) ) ? "" : " AND DV.id='".$aData["division_id"]."'";
		}
		$sql 	= " SELECT  DV.*
					FROM m_division AS DV
					WHERE 1 = 1  $WHERE
					ORDER BY DV.id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_department( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  .= ( !isset($aData["department_id"]) ) ? "" : " AND DP.id='".$aData["department_id"]."'";
			$WHERE  .= ( !isset($aData["division_id"]) )   ? "" : " AND DP.m_division_id='".$aData["division_id"]."'";
		}
		$sql 	= " SELECT  DP.*
					FROM m_department AS DP
					WHERE 1 = 1  $WHERE
					ORDER BY DP.id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_position( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  .= ( !isset($aData["position_id"]) )   ? "" : " AND PS.id='".$aData["position_id"]."'";
			$WHERE  .= ( !isset($aData["division_id"]) )   ? "" : " AND PS.m_division_id='".$aData["division_id"]."'";
			$WHERE  .= ( !isset($aData["department_id"]) ) ? "" : " AND PS.m_department_id='".$aData["department_id"]."'";
		}
		$sql 	= " SELECT  PS.*
					FROM m_position AS PS
					WHERE 1 = 1  $WHERE
					ORDER BY PS.id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_status_employee( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  = ( $aData["status_employee_id"] == "" ) ? "" : " AND SE.id='".$aData["status_employee_id"]."'";
		}
		$sql 	= " SELECT  SE.*
					FROM m_status_employee AS SE
					WHERE 1 = 1  $WHERE
					ORDER BY SE.id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}


	public function search_quarter( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  .= ( !isset($aData["quarter_id"]) ) ? "" : " AND id='".$aData["quarter_id"]."'";
		}
		$sql 	= " SELECT * FROM m_quarter WHERE 1 = 1  $WHERE ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_province( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  .= ( !isset($aData["quarter_id"]) ) ? "" : " AND m_quarter_id='".$aData["quarter_id"]."'";
			$WHERE  .= ( !isset($aData["province_id"]) ) ? "" : " AND id='".$aData["province_id"]."'";
		}
		$sql 	= " SELECT * FROM `m_ province` WHERE 1 = 1  $WHERE ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_amphur( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  .= ( !isset($aData["amphur_id"]) ) 		? "" : " AND id='".$aData["amphur_id"]."'";
			$WHERE  .= ( !isset($aData["province_id"]) )    ? "" : " AND m_province_id='".$aData["province_id"]."'";
		}
		$sql 	= " SELECT * FROM m_amphur WHERE 1 = 1  $WHERE ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_district( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  .= ( !isset($aData["district_id"]) ) 	? "" : " AND id='".$aData["district_id"]."'";
			$WHERE  .= ( !isset($aData["amphur_id"]) )    	? "" : " AND m_amphur_id='".$aData["amphur_id"]."'";
			$WHERE  .= ( !isset($aData["province_id"]) )    ? "" : " AND m_province_id='".$aData["province_id"]."'";
		}
		$sql 	= " SELECT * FROM m_district WHERE 1 = 1  $WHERE ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_hotel( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  .= ( !isset($aData["hotel_id"]) ) 		? "" : " AND id ='".$aData["hotel_id"]."'";
			$WHERE  .= ( !isset($aData["hotel_code"]) )    	? "" : " AND code ='".$aData["hotel_code"]."'";
		}
		$sql 	= " SELECT * FROM hotel WHERE 1 = 1  $WHERE ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_hotel_use( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  .= ( !isset($aData["hotel_id"]) ) 		? "" : " AND id ='".$aData["hotel_id"]."'";
			$WHERE  .= ( !isset($aData["hotel_code"]) )    	? "" : " AND code ='".$aData["hotel_code"]."'";
		}
		$sql 	= " SELECT * FROM hotel WHERE 1 = 1  $WHERE AND m_status_hotel_id = 1 ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}
	
}