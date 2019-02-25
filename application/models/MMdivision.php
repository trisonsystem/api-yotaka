<?php
class MMdivision extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_division( $aData ){
        $lm = 15;
        if ( !isset($aData["page"]) ) 		 	    { $aData["page"] 				= 1;}
        if ( !isset($aData["division_code"]) ) 	    { $aData["division_code"] 		= "";}
        if ( !isset($aData["division_name"]) ) 	    { $aData["division_name"] 		= "";}
        if ( !isset($aData["division_status"]) ) 	{ $aData["division_status"] 	= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["division_code"] 		== "" ) ? "" : " AND DV.code='".$aData["division_code"]."'";
        $WHERE  .= ( $aData["division_name"] 		== "" ) ? "" : " AND DV.name='".$aData["division_name"]."'";
        $WHERE  .= ( $aData["division_status"] 		== "" ) ? "" : " AND DV.status='".$aData["division_status"]."'";
        $WHERE  .= " AND DV.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT DV.*
                FROM m_division AS DV
                WHERE 1 = 1 $WHERE
                ORDER BY DV.id DESC
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
        $aSave 	 = array();
        // debug($aData,true);
        $aSave["code"] 	= $aData["txtDivisionCode"];
		$aSave["name"] 	= $aData["txtDivisionName"];
        $aSave["status"] 	= "1";
        if ($aData['txtDivision_id'] == "0") {
            $aSave["hotel_id"] 		= $aData["hotel_id"];
            $aSave["create_date"] 	= date("Y-m-d H:i:s");
            $aSave["create_by"] 	= $aData["user"];
            $aSave["update_date"] 	= date("Y-m-d H:i:s");
            $aSave["update_by"] 	= $aData["user"];

            if ($this->db->replace('m_division', $aSave)) {
				$aReturn["flag"] = true;
				$aReturn["msg"] = "success";
			}else{
				$aReturn["flag"] = false;
				$aReturn["msg"] = "Error SQL !!!";
			}
        } else {
            
            $aSave["update_date"] 			= date("Y-m-d H:i:s");
            $aSave["update_by"] 			= $aData["user"];
            $this->db->where("id", $aData["txtDivision_id"] );
			if ($this->db->update('m_division', $aSave)) {
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
		$aSave["update_date"] 			= date("Y-m-d H:i:s");
		$aSave["update_by"] 			= $aData["user"];
		$aSave["status"] 	= $aData["status"];
		$this->db->where("id", $aData["division_id"] );
		if ($this->db->update('m_division', $aSave)) {
			$aReturn["flag"] = true;
			$aReturn["msg"] = "success";
		}else{
			$aReturn["flag"] = false;
			$aReturn["msg"] = "Error SQL !!!";
		}

		return $aReturn;
	}
}