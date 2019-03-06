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

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["roomitem_id"]      == "" ) ? "" : " AND RI.id='".$aData["roomitem_id"]."'";
        $WHERE  .= ( $aData["roomitem_name"] 		== "" ) ? "" : " AND RI.name LIKE '%".$aData["roomitem_name"]."%'";
        $WHERE  .= ( $aData["roomitem_status"]        == "" ) ? "" : " AND RI.status='".$aData["roomitem_status"]."'";
        $WHERE  .= " AND RI.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT RI.*
				FROM m_room_item AS RI
				
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

    public function save_data( $aData ){
    	$aReturn = array();
        $arrParam = array('txtRoomItem_id', 'etxtRoomItemName', 'txtRoomItem_status');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }

        $aSave   = array();
        $aSave["name"]  = $aData["etxtRoomItemName"];
        
        if ($aData['txtRoomItem_id'] == "0") {
            $aSave["status"]    = "1";
            $aSave["hotel_id"]      = $aData["hotel_id"];
            $aSave["create_date"]   = date("Y-m-d H:i:s");
            $aSave["create_by"]     = $aData["user"];
            $aSave["update_date"]   = date("Y-m-d H:i:s");
            $aSave["update_by"]     = $aData["user"];

            if ($this->db->replace('m_room_item', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        } else {
            $aSave["status"]    = $aData["txtRoomItem_status"];
            $aSave["update_date"]           = date("Y-m-d H:i:s");
            $aSave["update_by"]             = $aData["user"];
            $this->db->where("id", $aData["txtRoomItem_id"] );
            if ($this->db->update('m_room_item', $aSave)) {
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
        $this->db->where("id", $aData["roomitem_id"] );
        if ($this->db->update('m_room_item', $aSave)) {
            $aReturn["flag"] = true;
            $aReturn["msg"] = "success";
        }else{
            $aReturn["flag"] = false;
            $aReturn["msg"] = "Error SQL !!!";
        }

        return $aReturn;
    }
}