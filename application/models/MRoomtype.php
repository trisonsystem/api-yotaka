<?php
class MRoomtype extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_roomtype( $aData ){
    	$lm = 15;
        if ( !isset($aData["page"]) ) 		 	   	{ $aData["page"] 				= 1;}
        if ( !isset($aData["roomtype_id"]) )        { $aData["roomtype_id"]     = "";}
        if ( !isset($aData["roomtype_name"]) ) 	    { $aData["roomtype_name"] 	= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["roomtype_id"]      == "" ) ? "" : " AND RT.id='".$aData["roomtype_id"]."'";
        $WHERE  .= ( $aData["roomtype_name"] 		== "" ) ? "" : " AND RT.name LIKE '%".$aData["roomtype_name"]."%'";
        $WHERE  .= " AND RT.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT *
				FROM m_room_type AS RT
                WHERE 1 = 1 $WHERE
                ORDER BY RT.id DESC LIMIT $LIMIT";
               
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