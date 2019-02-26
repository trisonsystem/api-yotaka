<?php
class MDepartment extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_department( $aData ){

        $lm = 15;
        if ( !isset($aData["page"]) ) 		 	    	{ $aData["page"] 				= 1;}
        if ( !isset($aData["department_id"]) )        { $aData["department_id"]     = "";}
        if ( !isset($aData["department_code"]) ) 	    { $aData["department_code"] 	= "";}
        if ( !isset($aData["department_name"]) ) 	    { $aData["department_name"] 	= "";}
        if ( !isset($aData["m_division_id"]) )          { $aData["m_division_id"]     = "";}
        if ( !isset($aData["department_status"]) ) 		{ $aData["department_status"] 	= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["department_id"]      == "" ) ? "" : " AND DP.id='".$aData["department_id"]."'";
        $WHERE  .= ( $aData["department_code"] 		== "" ) ? "" : " AND DP.code LIKE '%".$aData["department_code"]."%'";
        $WHERE  .= ( $aData["department_name"] 		== "" ) ? "" : " AND DP.name LIKE '%".$aData["department_name"]."%'";
        $WHERE  .= ( $aData["m_division_id"]        == "" ) ? "" : " AND DP.m_division_id='".$aData["m_division_id"]."'";
        $WHERE  .= ( $aData["department_status"] 	== "" ) ? "" : " AND DP.status='".$aData["department_status"]."'";
        $WHERE  .= " AND DP.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT DP.*, DV.name AS division_name
                FROM m_department AS DP
                LEFT JOIN m_division AS DV ON DP.m_division_id = DV.id
                WHERE 1 = 1 $WHERE
                ORDER BY DP.id DESC LIMIT $LIMIT";

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
        $aSave["code"]  = $aData["textDepartmentCode"];
        $aSave["name"]  = $aData["textDepartmentName"];
        $aSave["m_division_id"]  = $aData["sleName_Division"];
        $aSave["status"]    = "1";
        if ($aData['txtDepartment_id'] == "0") {
            $aSave["hotel_id"]      = $aData["hotel_id"];
            $aSave["create_date"]   = date("Y-m-d H:i:s");
            $aSave["create_by"]     = $aData["user"];
            $aSave["update_date"]   = date("Y-m-d H:i:s");
            $aSave["update_by"]     = $aData["user"];

            if ($this->db->replace('m_department', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        } else {
            
            $aSave["update_date"]           = date("Y-m-d H:i:s");
            $aSave["update_by"]             = $aData["user"];
            $this->db->where("id", $aData["txtDepartment_id"] );
            if ($this->db->update('m_department', $aSave)) {
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
        $this->db->where("id", $aData["department_id"] );
        if ($this->db->update('m_department', $aSave)) {
            $aReturn["flag"] = true;
            $aReturn["msg"] = "success";
        }else{
            $aReturn["flag"] = false;
            $aReturn["msg"] = "Error SQL !!!";
        }

        return $aReturn;
    }
}