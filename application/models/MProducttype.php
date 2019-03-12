<?php
class MProducttype extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_producttype( $aData ){
    	$lm = 15;
        if ( !isset($aData["page"]) ) 		 	   	{ $aData["page"] 				= 1;}
        if ( !isset($aData["producttype_id"]) )        { $aData["producttype_id"]     = "";}
        if ( !isset($aData["producttype_name"]) ) 	    { $aData["producttype_name"] 	= "";}
        if ( !isset($aData["producttype_status"]) )    { $aData["producttype_status"]     = "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["producttype_id"]      == "" ) ? "" : " AND PT.id='".$aData["producttype_id"]."'";
        $WHERE  .= ( $aData["producttype_name"] 		== "" ) ? "" : " AND PT.name LIKE '%".$aData["producttype_name"]."%'";
        $WHERE  .= ( $aData["producttype_status"]        == "" ) ? "" : " AND PT.status='".$aData["producttype_status"]."'";
        // $WHERE  .= " AND DP.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT * FROM m_product_type AS PT
                WHERE 1 = 1 $WHERE
                ORDER BY PT.id DESC LIMIT $LIMIT";
                // debug($sql, true);
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