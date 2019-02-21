<?php
class MHotel extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function search_hotel( $aData ){
		$lm = 15;
		if ( !isset($aData["page"]) ) 		 	{ $aData["page"] 				= 1;}
		if ( !isset($aData["hotel_code"]) ) 	{ $aData["hotel_code"] 			= "";}
		if ( !isset($aData["hotel_name"]) ) 	{ $aData["hotel_name"] 			= "";}
		if ( !isset($aData["hotel_owner"]) ) 	{ $aData["hotel_owner"] 		= "";}
		if ( !isset($aData["quarter_id"]) ) 	{ $aData["quarter_id"] 			= "";}
		if ( !isset($aData["province_id"]) ) 	{ $aData["province_id"] 		= "";}
		if ( !isset($aData["amphur_id"]) ) 		{ $aData["amphur_id"] 			= "";}
		if ( !isset($aData["hotel_id"]) ) 		{ $aData["hotel_id"] 			= "";}

		$LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

		$WHERE   = "";
		$WHERE  .= ( $aData["hotel_id"] 		== "" ) ? "" : " AND HT.id='".$aData["hotel_id"]."'";
		$WHERE  .= ( $aData["hotel_code"] 		== "" ) ? "" : " AND HT.code LIKE '%".$aData["hotel_code"]."%'";
		$WHERE  .= ( $aData["hotel_name"] 		== "" ) ? "" : " AND HT.name_th LIKE '%".$aData["hotel_name"]."%'";
		$WHERE  .= ( $aData["hotel_owner"] 		== "" ) ? "" : " AND HT.fullname_owner LIKE '%".$aData["hotel_owner"]."%'";
		$WHERE  .= ( $aData["quarter_id"] 		== "" ) ? "" : " AND HT.m_quarter_id ='".$aData["quarter_id"]."'";
		$WHERE  .= ( $aData["province_id"] 		== "" ) ? "" : " AND HT.m_province_id ='".$aData["province_id"]."'";
		$WHERE  .= ( $aData["amphur_id"] 		== "" ) ? "" : " AND HT.m_amphur_id='".$aData["amphur_id"]."'";

		$sql 	= "SELECT  HT.*, 
						QT.name_th AS quarter_name_th, 
						QT.name_en AS quarter_name_en, 
						PV.name AS province_name,
						AP.name AS amphur_name
					FROM hotel AS HT
					LEFT JOIN m_quarter 	AS QT ON QT.id = HT.m_quarter_id
					LEFT JOIN `m_ province` AS PV ON PV.id = HT.m_province_id
					LEFT JOIN m_amphur 		AS AP ON AP.id = HT.m_amphur_id
					WHERE 1 = 1  $WHERE
					ORDER BY HT.id DESC
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

	public function save_data( $aData ){
		$aReturn  = array();
		$code 	  = ($aData["txtHotel_id"] == "0") ? $this->create_hotel_code() : $aData["txtHotel_code"] ;
		$fodel 	  = "assets/upload/hotel_profile/";
		$aFN 	  = explode(".", $aData["txtHotelProfile"]);
        $n_name   = $aFN[count($aFN)-1];
        $n_path   = $fodel.$code.".".$n_name;

        $aSave 	 = array();
		$aSave["m_quarter_id"] 		= $aData["slQuarter"];
		$aSave["m_province_id"] 	= $aData["slProvince"];
		$aSave["m_amphur_id"] 		= $aData["slAmphur"];
		$aSave["m_district_id"] 	= $aData["slDistrict"];
		$aSave["postcode"] 			= $aData["txtPostcode"];
		$aSave["tax_number"] 		= $aData["txtNumberTax"];
		$aSave["address"] 			= $aData["txtAddress"];
		$aSave["tel"] 				= $aData["txtTel"];
		$aSave["email"] 			= $aData["txtEmail"];
		$aSave["contact_other"] 	= $aData["txtContactOther"];
		$aSave["fullname_owner"] 	= $aData["txtFullNameOwner"];
		$aSave["name_th"] 			= $aData["txtNameTH"];
		$aSave["name_en"] 			= $aData["txtNameEN"];
		if ($aData["txtHotelProfile"] != "0") {
			$aSave["profile_img"] 		= $n_path;
		}
		if ($aData["txtHotel_id"] == "0") {
			$aSave["code"] 					= $code;
			$aSave["m_status_hotel_id"]		= "1";
			$aSave["create_date"] 			= date("Y-m-d H:i:s");
			$aSave["create_by"] 			= "zztop";
			$aSave["update_date"] 			= date("Y-m-d H:i:s");
			$aSave["update_by"] 			= "zztop";

			if ($this->db->replace('hotel', $aSave)) {
				$aReturn["flag"] = true;
				$aReturn["msg"] = "success";
				$aReturn["code"] = $code;
			}else{
				$aReturn["flag"] = false;
				$aReturn["msg"] = "Error SQL !!!";
			}
		}else{
			$aSave["update_date"] 			= date("Y-m-d H:i:s");
			$aSave["update_by"] 			= "zztop";
			$this->db->where("id", $aData["txtHotel_id"] );
			if ($this->db->update('hotel', $aSave)) {
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

	function create_hotel_code(){
		$time   = substr(date("Y") + 543, 2).date("m");
		$sql 	= " SELECT 	MAX( HT.code ) AS hotel_code
					FROM hotel AS HT
					WHERE HT.code LIKE CONCAT('HT-',$time, '%')";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		if (count($arr) > 0) {
			$aCode = explode("-", $arr[0]["hotel_code"]);
			$code  = $aCode[1]+1;
			$code  = number_format("0.".$code, 7);
			$code  = $aCode[0]."-".substr($code, 2);
		}
	
		return $code;
	}


	function search_status_hotel( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  = ( !isset( $aData["status_hotel_id"] ) ) ? "" : " AND id='".$aData["status_hotel_id"]."'";
		}
		$sql 	= " SELECT  * FROM m_status_hotel WHERE 1 = 1  $WHERE ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	function chang_status( $aData ){
		$aSave["update_date"] 			= date("Y-m-d H:i:s");
		$aSave["update_by"] 			= "zztop";
		$aSave["m_status_hotel_id"] 	= $aData["status"];
		$this->db->where("id", $aData["hotel_id"] );
		if ($this->db->update('hotel', $aSave)) {
			$aReturn["flag"] = true;
			$aReturn["msg"] = "success";
		}else{
			$aReturn["flag"] = false;
			$aReturn["msg"] = "Error SQL !!!";
		}

		return $aReturn;
	}

}

?>