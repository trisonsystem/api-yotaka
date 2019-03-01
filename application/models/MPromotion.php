<?php
class MPromotion extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_promotion( $aData ){
    	$lm = 15;
        if ( !isset($aData["page"]) ) 		 	   	{ $aData["page"] 				= 1;}
        if ( !isset($aData["promotion_id"]) )        { $aData["promotion_id"]     = "";}
        if ( !isset($aData["promotion_code"]) ) 	    { $aData["promotion_code"] 	= "";}
        if ( !isset($aData["promotion_title"]) ) 	    { $aData["promotion_title"] 	= "";}
        if ( !isset($aData["promotion_status"]) ) 		{ $aData["promotion_status"] 	= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["promotion_id"]      == "" ) ? "" : " AND PM.id='".$aData["promotion_id"]."'";
        $WHERE  .= ( $aData["promotion_code"] 		== "" ) ? "" : " AND PM.promotion_code LIKE '%".$aData["promotion_code"]."%'";
        $WHERE  .= ( $aData["promotion_title"] 		== "" ) ? "" : " AND PM.title LIKE '%".$aData["promotion_title"]."%'";
        $WHERE  .= ( $aData["promotion_status"] 	== "" ) ? "" : " AND PM.status='".$aData["promotion_status"]."'";
        $WHERE  .= " AND PM.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT *
				FROM promotion AS PM
                WHERE 1 = 1 $WHERE
                ORDER BY PM.id DESC LIMIT $LIMIT";

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