<?php
class MEmployeestatus extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_employeestatus( $aData ){
    	$lm = 15;
        if ( !isset($aData["page"]) ) 		 	   	{ $aData["page"] 				= 1;}
        if ( !isset($aData["employeestatus_id"]) )        { $aData["employeestatus_id"]     = "";}
        if ( !isset($aData["employeestatus_name"]) ) 	    { $aData["employeestatus_name"] 	= "";}
        if ( !isset($aData["employeestatus_status"]) ) 	    { $aData["employeestatus_status"] 	= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["employeestatus_id"]      == "" ) ? "" : " AND SE.id='".$aData["employeestatus_id"]."'";
        $WHERE  .= ( $aData["employeestatus_name"] 		== "" ) ? "" : " AND SE.name LIKE '%".$aData["employeestatus_name"]."%'";
        $WHERE  .= ( $aData["employeestatus_status"]      == "" ) ? "" : " AND SE.status='".$aData["employeestatus_status"]."'";
        // $WHERE  .= " AND SE.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT *
				FROM m_status_employee AS SE
                WHERE 1 = 1 $WHERE
                ORDER BY SE.id DESC LIMIT $LIMIT";

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
        $arraParam = array('txtEmployeeStatus_id', 'etxtEmployeeStatusName', 'txtEmployeeStatus_status');

        $aSave   = array();
        $aSave["name"]  = $aData["etxtEmployeeStatusName"];
        
        if ($aData['txtEmployeeStatus_id'] == "0") {
            $aSave["status"]    = "1";
            // $aSave["hotel_id"]      = $aData["hotel_id"];
            $aSave["create_date"]   = date("Y-m-d H:i:s");
            $aSave["create_by"]     = $aData["user"];
            $aSave["update_date"]   = date("Y-m-d H:i:s");
            $aSave["update_by"]     = $aData["user"];

            if ($this->db->replace('m_status_employee', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        } else {
            $aSave["status"]                = $aData["txtEmployeeStatus_status"];
            $aSave["update_date"]           = date("Y-m-d H:i:s");
            $aSave["update_by"]             = $aData["user"];
            $this->db->where("id", $aData["txtEmployeeStatus_id"] );
            if ($this->db->update('m_status_employee', $aSave)) {
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
        $this->db->where("id", $aData["employeestatus_id"] );
        if ($this->db->update('m_status_employee', $aSave)) {
            $aReturn["flag"] = true;
            $aReturn["msg"] = "success";
        }else{
            $aReturn["flag"] = false;
            $aReturn["msg"] = "Error SQL !!!";
        }

        return $aReturn;
    }
}