<?php
class MRoomitem extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_roomitem( $aData ){
    	$lm = 15;
        if ( !isset($aData["page"]) ) 		 	   	{ $aData["page"] 				= 1;}
        if ( !isset($aData["roomitem_id"]) )        { $aData["roomitem_id"]     = "";}
        if ( !isset($aData["roomitem_name"]) ) 	    { $aData["roomitem_name"] 	= "";}
        if ( !isset($aData["roomitem_status"]) )    { $aData["roomitem_status"]     = "";}
        if ( !isset($aData["room_code"]) ) 	    { $aData["room_code"] 	= "";}
        if ( !isset($aData["room_name"]) )    { $aData["room_name"]     = "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["roomitem_id"]      == "" ) ? "" : " AND RI.id='".$aData["roomitem_id"]."'";
        $WHERE  .= ( $aData["roomitem_name"] 		== "" ) ? "" : " AND RI.name LIKE '%".$aData["roomitem_name"]."%'";
        $WHERE  .= ( $aData["roomitem_status"]        == "" ) ? "" : " AND RI.status='".$aData["roomitem_status"]."'";
        $WHERE  .= ( $aData["room_code"] 		== "" ) ? "" : " AND RM.code LIKE '%".$aData["room_code"]."%'";
        $WHERE  .= ( $aData["room_name"]        == "" ) ? "" : " AND RM.name LIKE '%".$aData["room_name"]."%'";
        $WHERE  .= " AND RI.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT RI.*, RM.code AS  room_code, RM.name AS room_name
				FROM m_room_item AS RI
				LEFT JOIN m_room AS RM ON RI.m_room_id = RM.id
                WHERE 1 = 1 $WHERE
                ORDER BY RI.id DESC LIMIT $LIMIT";

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