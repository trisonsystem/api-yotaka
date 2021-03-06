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
        if ( !isset($aData["roomtype_status"]) ) 	    { $aData["roomtype_status"] 	= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["roomtype_id"]      == "" ) ? "" : " AND RT.id='".$aData["roomtype_id"]."'";
        $WHERE  .= ( $aData["roomtype_name"] 		== "" ) ? "" : " AND RT.name LIKE '%".$aData["roomtype_name"]."%'";
        $WHERE  .= ( $aData["roomtype_status"]      == "" ) ? "" : " AND RT.status='".$aData["roomtype_status"]."'";
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

    public function save_data( $aData ){
    	$aReturn = array();
        $arrParam = array('txtRoomType_id', 'etxtRoomTypeName', 'txtRoomType_status');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }

        $aSave   = array();
        $aSave["name"]  = $aData["etxtRoomTypeName"];
        
        if ($aData['txtRoomType_id'] == "0") {
            $aSave["status"]    = "1";
            $aSave["hotel_id"]      = $aData["hotel_id"];
            $aSave["create_date"]   = date("Y-m-d H:i:s");
            $aSave["create_by"]     = $aData["user"];
            $aSave["update_date"]   = date("Y-m-d H:i:s");
            $aSave["update_by"]     = $aData["user"];

            if ($this->db->replace('m_room_type', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        } else {
            $aSave["status"]                = $aData["txtRoomType_status"];
            $aSave["update_date"]           = date("Y-m-d H:i:s");
            $aSave["update_by"]             = $aData["user"];
            $this->db->where("id", $aData["txtRoomType_id"] );
            if ($this->db->update('m_room_type', $aSave)) {
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
        $this->db->where("id", $aData["roomtype_id"] );
        if ($this->db->update('m_room_type', $aSave)) {
            $aReturn["flag"] = true;
            $aReturn["msg"] = "success";
        }else{
            $aReturn["flag"] = false;
            $aReturn["msg"] = "Error SQL !!!";
        }

        return $aReturn;
    }
}