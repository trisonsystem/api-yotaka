<?php
class MMposition extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_position( $aData ){
    	$lm = 15;
        if ( !isset($aData["page"]) ) 		 	    	{ $aData["page"] 				= 1;}
        if ( !isset($aData["position_id"]) )        { $aData["position_id"]     = "";}
        if ( !isset($aData["position_code"]) ) 	    { $aData["position_code"] 	= "";}
        if ( !isset($aData["position_name"]) ) 	    { $aData["position_name"] 	= "";}
        if ( !isset($aData["position_status"]) )          { $aData["position_status"]     = "";}
        // if ( !isset($aData["department_status"]) ) 		{ $aData["department_status"] 	= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["position_id"]      == "" ) ? "" : " AND PS.id='".$aData["position_id"]."'";
        $WHERE  .= ( $aData["position_code"] 		== "" ) ? "" : " AND PS.code LIKE '%".$aData["position_code"]."%'";
        $WHERE  .= ( $aData["position_name"] 		== "" ) ? "" : " AND PS.name LIKE '%".$aData["position_name"]."%'";
        $WHERE  .= ( $aData["position_status"]        == "" ) ? "" : " AND PS.status='".$aData["position_status"]."'";
        // $WHERE  .= ( $aData["department_status"] 	== "" ) ? "" : " AND DP.status='".$aData["department_status"]."'";
        // $WHERE  .= " AND DP.hotel_id='".$aData["hotel_id"]."'";

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