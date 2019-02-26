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
        if ( !isset($aData["department_code"]) ) 	    { $aData["department_code"] 	= "";}
        if ( !isset($aData["department_name"]) ) 	    { $aData["department_name"] 	= "";}
        if ( !isset($aData["department_status"]) ) 		{ $aData["department_status"] 	= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["department_code"] 		== "" ) ? "" : " AND DP.code='".$aData["department_code"]."'";
        $WHERE  .= ( $aData["department_name"] 		== "" ) ? "" : " AND DP.name='".$aData["department_name"]."'";
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
}