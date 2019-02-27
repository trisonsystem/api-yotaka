<?php
class MMposition extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_position( $aData ){
    	$lm = 15;
        if ( !isset($aData["page"]) ) 		 	   	{ $aData["page"] 				= 1;}
        if ( !isset($aData["position_id"]) )        { $aData["position_id"]     = "";}
        if ( !isset($aData["position_code"]) ) 	    { $aData["position_code"] 	= "";}
        if ( !isset($aData["position_name"]) ) 	    { $aData["position_name"] 	= "";}
        if ( !isset($aData["position_status"]) )    { $aData["position_status"]     = "";}
        if ( !isset($aData["division_id"]) ) 		{ $aData["division_id"] 	= "";}
        if ( !isset($aData["department_id"]) ) 		{ $aData["department_id"] 	= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["position_id"]      == "" ) ? "" : " AND PS.id='".$aData["position_id"]."'";
        $WHERE  .= ( $aData["position_code"] 		== "" ) ? "" : " AND PS.code LIKE '%".$aData["position_code"]."%'";
        $WHERE  .= ( $aData["position_name"] 		== "" ) ? "" : " AND PS.name LIKE '%".$aData["position_name"]."%'";
        $WHERE  .= ( $aData["position_status"]        == "" ) ? "" : " AND PS.status='".$aData["position_status"]."'";
        $WHERE  .= ( $aData["division_id"] 	== "" ) ? "" : " AND PS.m_division_id='".$aData["division_id"]."'";
        $WHERE  .= ( $aData["department_id"] 	== "" ) ? "" : " AND PS.m_department_id='".$aData["department_id"]."'";
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

    public function search_division( $aData ){
    	$WHERE  = "";
        if ($aData != "") {
            $WHERE  .= " AND DV.hotel_id='".$aData["hotel_id"]."'";
        }
        $sql    = " SELECT  DV.*
                    FROM m_division AS DV
                    WHERE 1 = 1  $WHERE
                    ORDER BY DV.id ASC";
        $query  = $this->db->query($sql);
        
        $arr = array();
        foreach ($query->result_array() as $key => $value) {
            $arr[] = $value;
        }       
        // debug($arr);
        return $arr;
    }

    public function save_data( $aData ){
    	$aReturn = array();
        $aSave   = array();
        // debug($aData);
        $aSave["code"]  = $aData["etxtPositionCode"];
        $aSave["name"]  = $aData["etxtPositionName"];
        $aSave["m_division_id"]  = $aData["eslPositionDivision"];
        $aSave["m_department_id"]  = $aData["eslPositionDepartment"];
        
        if ($aData['txtPosition_id'] == "0") {
            $aSave["status"]    = "1";
            $aSave["hotel_id"]      = $aData["hotel_id"];
            $aSave["create_date"]   = date("Y-m-d H:i:s");
            $aSave["create_by"]     = $aData["user"];
            $aSave["update_date"]   = date("Y-m-d H:i:s");
            $aSave["update_by"]     = $aData["user"];

            if ($this->db->replace('m_position', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        } else {
            $aSave["status"]    = $aData["txtPosition_status"];
            $aSave["update_date"]           = date("Y-m-d H:i:s");
            $aSave["update_by"]             = $aData["user"];
            $this->db->where("id", $aData["txtPosition_id"] );
            if ($this->db->update('m_position', $aSave)) {
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
        $this->db->where("id", $aData["position_id"] );
        if ($this->db->update('m_position', $aSave)) {
            $aReturn["flag"] = true;
            $aReturn["msg"] = "success";
        }else{
            $aReturn["flag"] = false;
            $aReturn["msg"] = "Error SQL !!!";
        }

        return $aReturn;
    }
}