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
		// debug($sql);
		return $arr;
	}

	public function search_banknumberlist( $aData ){
		$lm = 15;
        if ( !isset($aData["page"]) ) 		 	   	{ $aData["page"] 				= 1;}
        if ( !isset($aData["account_id"]) )        	{ $aData["account_id"]     		= "";}
        if ( !isset($aData["account_number"]) ) 	{ $aData["account_number"] 		= "";}
        if ( !isset($aData["account_name"]) ) 	    { $aData["account_name"] 		= "";}
        if ( !isset($aData["account_status"]) )    	{ $aData["account_status"]     	= "";}
        if ( !isset($aData["bank_id"]) ) 			{ $aData["bank_id"] 			= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["account_id"]      == "" ) ? "" : " AND BK.id='".$aData["account_id"]."'";
        $WHERE  .= ( $aData["account_number"] 		== "" ) ? "" : " AND BK.account_number LIKE '%".$aData["account_number"]."%'";
        $WHERE  .= ( $aData["account_name"] 		== "" ) ? "" : " AND BK.account_name LIKE '%".$aData["account_name"]."%'";
        $WHERE  .= ( $aData["account_status"]        == "" ) ? "" : " AND BK.status='".$aData["account_status"]."'";
        $WHERE  .= ( $aData["bank_id"] 	== "" ) ? "" : " AND BK.m_bank_id='".$aData["bank_id"]."'";
        $WHERE  .= " AND BK.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT BK.*, B.name_th, B.name_en
				FROM m_bank_number_list AS BK
				LEFT JOIN m_bank AS B ON BK.m_bank_id = B.id
				WHERE 1 = 1 $WHERE 
				ORDER BY BK.id ";

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
        $arrParam = array('eslBankName', 'etxtAccountNumber', 'etxtAccountName', 'txtAccount_id', 'txtAccount_status', 'hotel_id');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }

        $aSave   = array();
        $aSave["m_bank_id"]  = $aData["eslBankName"];
        $aSave["account_number"]  = $aData["etxtAccountNumber"];
        $aSave["account_name"]  = $aData["etxtAccountName"];
        
        if ($aData['txtAccount_id'] == "0") {
            $aSave["status"]    = "1";
            $aSave["hotel_id"]      = $aData["hotel_id"];
            $aSave["create_date"]   = date("Y-m-d H:i:s");
            $aSave["create_by"]     = $aData["user"];
            $aSave["update_date"]   = date("Y-m-d H:i:s");
            $aSave["update_by"]     = $aData["user"];

            if ($this->db->replace('m_bank_number_list', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        } else {
            $aSave["status"]    = $aData["txtAccount_status"];
            $aSave["update_date"]           = date("Y-m-d H:i:s");
            $aSave["update_by"]             = $aData["user"];
            $this->db->where("id", $aData["txtAccount_id"] );
            if ($this->db->update('m_bank_number_list', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        }

        return $aReturn;

	}

	public function chang_status( $aData ){
		$aSave["update_date"]           = date("Y-m-d H:i:s");
        $aSave["update_by"]             = $aData["user"];
        $aSave["status"]    = $aData["status"];
        $this->db->where("id", $aData["account_id"] );
        if ($this->db->update('m_bank_number_list', $aSave)) {
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