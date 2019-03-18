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

	public function search_bankallname( $aData ){
		$arrParam = array('hotel_id');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }
		$WHERE   = "";
		$WHERE  .= " AND BK.hotel_id='".$aData["hotel_id"]."'";

		$sql 	= "	SELECT BK.*
					FROM m_bank AS BK
					WHERE 1 = 1 AND BK.`status` =1
					ORDER BY BK.id DESC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		// debug($arr);
		return $arr;
	}

	public function search_banknumberlist( $aData ){
		$lm = 15;
        if ( !isset($aData["page"]) ) 		 	   	{ $aData["page"] 				= 1;}
        if ( !isset($aData["account_id"]) )        { $aData["account_id"]     = "";}
        if ( !isset($aData["account_number"]) ) 	    { $aData["account_number"] 	= "";}
        if ( !isset($aData["account_name"]) ) 	    { $aData["account_name"] 	= "";}
        if ( !isset($aData["account_status"]) )    { $aData["account_status"]     = "";}
        if ( !isset($aData["bank_id"]) )    { $aData["bank_id"]     = "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["account_id"]      == "" ) ? "" : " AND PS.id='".$aData["account_id"]."'";
        $WHERE  .= ( $aData["account_number"] 		== "" ) ? "" : " AND PS.code LIKE '%".$aData["account_number"]."%'";
        $WHERE  .= ( $aData["account_name"] 		== "" ) ? "" : " AND PS.name LIKE '%".$aData["account_name"]."%'";
        $WHERE  .= ( $aData["account_status"]        == "" ) ? "" : " AND PS.status='".$aData["account_status"]."'";
        $WHERE  .= ( $aData["bank_id"] 	== "" ) ? "" : " AND PS.m_division_id='".$aData["bank_id"]."'";
        $WHERE  .= " AND DP.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT PS.*, DV.name AS division_name, DP.name AS department_name
				FROM m_position AS PS
				LEFT JOIN m_division AS DV ON PS.m_division_id = DV.id
				LEFT JOIN m_department AS DP ON PS.m_department_id = DP.id
                WHERE 1 = 1 $WHERE
                ORDER BY PS.id DESC LIMIT $LIMIT";

        $query 	= $this->db->query($sql);
        $arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		$arr["limit"] = $lm;
		// debug($arr);
		return $arr;
	}

	
}
?>