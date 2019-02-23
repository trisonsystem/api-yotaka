<?php
class MEmployee extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function search_employee( $aData ){
		$lm = 15;
		if ( !isset($aData["page"]) ) 		 	{ $aData["page"] 				= 1;}
		if ( !isset($aData["employee_id"]) ) 	{ $aData["employee_id"] 		= "";}
		if ( !isset($aData["employee_code"]) ) 	{ $aData["employee_code"] 		= "";}
		if ( !isset($aData["employee_name"]) ) 	{ $aData["employee_name"] 		= "";}
		if ( !isset($aData["employee_lastname"]) ) { $aData["employee_lastname"] = "";}
		if ( !isset($aData["position_id"]) ) 	{ $aData["position_id"] 		= "";}
		if ( !isset($aData["department_id"]) ) 	{ $aData["department_id"] 		= "";}
		if ( !isset($aData["division_id"]) ) 	{ $aData["division_id"] 		= "";}
		if ( !isset($aData["employee_status"]) ){ $aData["employee_status"] 	= "";}

		$LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

		$WHERE   = "";
		$WHERE  .= ( $aData["employee_id"] 		== "" ) ? "" : " AND EM.id='".$aData["employee_id"]."'";
		$WHERE  .= ( $aData["employee_code"] 	== "" ) ? "" : " AND EM.code LIKE '%".$aData["employee_code"]."%'";
		$WHERE  .= ( $aData["employee_name"] 	== "" ) ? "" : " AND EM.name LIKE '%".$aData["employee_name"]."%'";
		$WHERE  .= ( $aData["employee_lastname"]== "" ) ? "" : " AND EM.last_name LIKE '%".$aData["employee_lastname"]."%'";
		$WHERE  .= ( $aData["position_id"] 		== "" ) ? "" : " AND PS.id='".$aData["position_id"]."'";
		$WHERE  .= ( $aData["department_id"] 	== "" ) ? "" : " AND DP.id='".$aData["department_id"]."'";
		$WHERE  .= ( $aData["division_id"] 		== "" ) ? "" : " AND DV.id='".$aData["division_id"]."'";
		$WHERE  .= ( $aData["employee_status"] 	== "" ) ? "" : " AND EM.m_status_employee_id='".$aData["employee_status"]."'";
		$WHERE  .= " AND EM.hotel_id='".$aData["hotel_id"]."'";

		$sql 	= "SELECT  EM.*, 
						PS.name AS position_name, 
						DP.name AS department_name,
						DV.name AS division_name,
						SE.name AS status_name
					FROM employee AS EM
					LEFT JOIN m_position 	AS PS ON PS.id = EM.m_position_id
					LEFT JOIN m_department 	AS DP ON DP.id = EM.m_department_id
					LEFT JOIN m_division 	AS DV ON DV.id = EM.m_division_id
					LEFT JOIN m_status_employee AS SE ON SE.id = EM.m_status_employee_id
					WHERE 1 = 1  $WHERE
					ORDER BY EM.id DESC
					LIMIT $LIMIT";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		$arr["limit"] = $lm;
		// debug($arr);
		return $arr;
	}

	public function search_division( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  = ( $aData["division_id"] == "" ) ? "" : " AND DV.id='".$aData["division_id"]."'";
			$WHERE  .= " AND DV.hotel_id='".$aData["hotel_id"]."'";
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
			$WHERE  .= " AND DP.hotel_id='".$aData["hotel_id"]."'";
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
			$WHERE  .= " AND PS.hotel_id='".$aData["hotel_id"]."'";
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
			$WHERE  = ( !isset($aData["status_employee_id"] ) ) ? "" : " AND SE.id='".$aData["status_employee_id"]."'";
			$WHERE  .= " AND SE.hotel_id='".$aData["hotel_id"]."'";
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

	public function save_data( $aData ){
		$aReturn = array();
		
		$code 	  = ($aData["txtEmployee_id"] == "0") ? $this->create_employee_code( $aData["slDivision"], $aData["slDepartment"], $aData["slPosition"] ) : $aData["txtEmployee_code"] ;
		$fodel 	  = "assets/upload/employee_profile/";
		$aFN 	  = explode(".", $aData["txtEmployeeProfile"]);
        $n_name   = $aFN[count($aFN)-1];
        $n_path   = $fodel.$code.".".$n_name;

        $aSave 	 = array();
		$aSave["m_division_id"] 	= $aData["slDivision"];
		$aSave["m_department_id"] 	= $aData["slDepartment"];
		$aSave["m_position_id"] 	= $aData["slPosition"];
		$aSave["prefix"] 			= $aData["txtPrefix"];
		$aSave["name"] 				= $aData["txtName"];
		$aSave["last_name"] 		= $aData["txtLastName"];
		$aSave["id_card"] 			= $aData["txtCardNumber"];
		$aSave["type_card"] 		= $aData["rTypeCard"];
		$aSave["address"] 			= $aData["txtAddress"];
		$aSave["tel"] 				= $aData["txtTel"];
		$aSave["email"] 			= $aData["txtEmail"];
		$aSave["hotel_id"] 			= $aData["hotel_id"];
		$aSave["rights"] 			= $aData["slRights"];
		$aSave["birthday"] 			= $this->convert_date_to_base( $aData["txtBirthday"] );
		if ($aData["txtEmployeeProfile"] != "0") {
			$aSave["profile_img"] 		= $n_path;
		}
		if ($aData["txtEmployee_id"] == "0") {
			$aSave["code"] 					= $code;
			$aSave["username"] 				= $code;//$aData["txtUsername"];
			$aSave["password"] 				= md5($aData["txtPassWord"]);
			$aSave["m_status_employee_id"] 	= "1";
			$aSave["create_date"] 			= date("Y-m-d H:i:s");
			$aSave["create_by"] 			= $aData["user"];
			$aSave["update_date"] 			= date("Y-m-d H:i:s");
			$aSave["update_by"] 			= $aData["user"];

			if ($this->db->replace('employee', $aSave)) {
				$aReturn["flag"] = true;
				$aReturn["msg"] = "success";
				$aReturn["code"] = $code;
			}else{
				$aReturn["flag"] = false;
				$aReturn["msg"] = "Error SQL !!!";
			}
		}else{
			$aSave["update_date"] 			= date("Y-m-d H:i:s");
			$aSave["update_by"] 			= $aData["user"];
			$this->db->where("id", $aData["txtEmployee_id"] );
			if ($this->db->update('employee', $aSave)) {
				$aReturn["flag"] = true;
				$aReturn["msg"] = "success";
				$aReturn["code"] = $code;
			}else{
				$aReturn["flag"] = false;
				$aReturn["msg"] = "Error SQL !!!";
			}
		}

		
		// debug($aSave);

		return $aReturn;

	}

	function convert_date_to_base($str_date){
		if ($str_date != "") {
			$aDate = explode("-", $str_date);
			return $aDate["2"]."-".$aDate["1"]."-".$aDate["0"];
		}
	}

	function create_employee_code($division_id, $department, $Position_id){
		$time   = "-".substr(date("Y") + 543, 2).date("m");
		$sql 	= " SELECT 	DV.code AS devision_code, 
							DP.code AS department_code,
							PS.code AS position_code,
							MAX( EM.code ) AS employee_code
					FROM m_division AS DV
					LEFT JOIN m_department AS DP ON DV.id = DP.m_division_id
					LEFT JOIN m_position   AS PS ON DV.id = DP.m_division_id AND DP.id = PS.m_department_id
					LEFT JOIN employee     AS EM ON EM.code LIKE CONCAT(DV.code ,DP.code, PS.code,$time, '%')";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		if (count($arr) > 0) {
			$aCode = explode("-", $arr[0]["employee_code"]);
			$code  = $aCode[1]+1;
			$code  = number_format("0.".$code, 7);
			$code  = $aCode[0]."-".substr($code, 2);
		}
	
		return $code;
	}

	function chang_status( $aData ){
		$aSave["update_date"] 			= date("Y-m-d H:i:s");
		$aSave["update_by"] 			= $aData["user"];
		$aSave["m_status_employee_id"] 	= $aData["status"];
		$this->db->where("id", $aData["employee_id"] );
		if ($this->db->update('employee', $aSave)) {
			$aReturn["flag"] = true;
			$aReturn["msg"] = "success";
		}else{
			$aReturn["flag"] = false;
			$aReturn["msg"] = "Error SQL !!!";
		}

		return $aReturn;
	}
}