<?php
class MCustomer extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function search_customer( $aData ){
		$lm = 15;
		if ( !isset($aData["page"]) ) 		 		{ $aData["page"] 				= 1;}
		if ( !isset($aData["customer_id"]) ) 		{ $aData["customer_id"] 		= "";}
		if ( !isset($aData["customer_cardnumber"]) ){ $aData["customer_cardnumber"] = "";}
		if ( !isset($aData["customer_name"]) ) 		{ $aData["customer_name"] 		= "";}
		if ( !isset($aData["customer_lastname"]) ) 	{ $aData["customer_lastname"] 	= "";}

		$LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

		$WHERE   = "";
		$WHERE  .= ( $aData["customer_id"] 		== "" ) ? "" : " AND CT.id='".$aData["customer_id"]."'";
		$WHERE  .= ( $aData["customer_cardnumber"] 	== "" ) ? "" : " AND CT.code LIKE '%".$aData["customer_cardnumber"]."%'";
		$WHERE  .= ( $aData["customer_name"] 	== "" ) ? "" : " AND CT.name LIKE '%".$aData["customer_name"]."%'";
		$WHERE  .= ( $aData["customer_lastname"]== "" ) ? "" : " AND CT.last_name LIKE '%".$aData["customer_lastname"]."%'";
		$WHERE  .= " AND CT.hotel_id='".$aData["hotel_id"]."'";

		$sql 	= "SELECT  CT.*, 
					CN.nation_name_th AS nation_name_th,
					CN.nation_name_en AS nation_name_en,
					CE.country_name_th AS ethnicity_th,
					CE.country_name_en AS ethnicity_en
					FROM m_customer AS CT
					LEFT JOIN m_country AS CN ON CT.nationality = CN.id
					LEFT JOIN m_country AS CE ON CT.ethnicity = CE.id
					WHERE 1 = 1  $WHERE
					ORDER BY CT.id DESC
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
		$aReturn = array();
		$arrParam = array('txtPrefix','txtName','txtLastName','txtCardNumber','rTypeCard','txtAddress','txtTel','txtEmail','hotel_id','txtBirthday','rUseSystem','slNationality','slEthnicity','txtCustomerProfile','txtCustomer_id');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }
        
		$code     = $aData["txtCardNumber"];
		$fodel 	  = "assets/upload/customer_profile/";
		$aFN 	  = explode(".", $aData["txtCustomerProfile"]);
        $n_name   = $aFN[count($aFN)-1];
        $n_path   = $fodel.$code.".".$n_name;

        $aSave 	 = array();
		$aSave["prefix"] 			= $aData["txtPrefix"];
		$aSave["name"] 				= $aData["txtName"];
		$aSave["last_name"] 		= $aData["txtLastName"];
		$aSave["id_card"] 			= $aData["txtCardNumber"];
		$aSave["type_card"] 		= $aData["rTypeCard"];
		$aSave["address"] 			= $aData["txtAddress"];
		$aSave["tel"] 				= $aData["txtTel"];
		$aSave["email"] 			= $aData["txtEmail"];
		$aSave["hotel_id"] 			= $aData["hotel_id"];
		$aSave["birthday"] 			= $this->convert_date_to_base( $aData["txtBirthday"] );
		$aSave["user_system"] 		= $aData["rUseSystem"];
		$aSave["nationality"] 		= $aData["slNationality"];
		$aSave["ethnicity"] 		= $aData["slEthnicity"];
		if ( isset($aData["txtUsername"]) && isset($aData["txtPassWord"]) ) {
			$aSave["username"] 		= ( isset($aData["txtUsername"]) ) ? $aData["txtUsername"] : "";
			$aSave["password"] 		= ( isset($aData["txtPassWord"]) ) ? md5($aData["txtPassWord"]) : "";
		}

		if ($aData["txtCustomerProfile"] != "0") {
			$aSave["profile_img"] 		= $n_path;
		}
		if ($aData["txtCustomer_id"] == "0") {
			$aSave["create_date"] 			= date("Y-m-d H:i:s");
			$aSave["create_by"] 			= $aData["user"];
			$aSave["update_date"] 			= date("Y-m-d H:i:s");
			$aSave["update_by"] 			= $aData["user"];

			if ($this->db->insert('m_customer', $aSave)) {
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
			$this->db->where("id", $aData["txtCustomer_id"] );
			if ($this->db->update('m_customer', $aSave)) {
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

}