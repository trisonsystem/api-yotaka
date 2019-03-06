<?php
class MHotelstatus extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search_hotelstatus( $aData ){
    	$lm = 15;
        if ( !isset($aData["page"]) ) 		 	   	{ $aData["page"] 				= 1;}
        if ( !isset($aData["hotelstatus_id"]) )        { $aData["hotelstatus_id"]     = "";}
        if ( !isset($aData["hotelstatus_name"]) ) 	    { $aData["hotelstatus_name"] 	= "";}
        if ( !isset($aData["hotelstatus_status"]) ) 	    { $aData["hotelstatus_status"] 	= "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["hotelstatus_id"]      == "" ) ? "" : " AND SH.id='".$aData["hotelstatus_id"]."'";
        $WHERE  .= ( $aData["hotelstatus_name"] 		== "" ) ? "" : " AND SH.name LIKE '%".$aData["hotelstatus_name"]."%'";
        $WHERE  .= ( $aData["hotelstatus_status"]      == "" ) ? "" : " AND SH.status='".$aData["hotelstatus_status"]."'";
        // $WHERE  .= " AND SE.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT *
				FROM m_status_hotel AS SH
                WHERE 1 = 1 $WHERE
                ORDER BY SH.id DESC LIMIT $LIMIT";

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
        $arrParam = array('txtHotelStatus_id', 'etxtHotelStatusName', 'txtHotelStatus_status');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }

        $aSave   = array();
        $aSave["name"]  = $aData["etxtHotelStatusName"];
        
        if ($aData['txtHotelStatus_id'] == "0") {
            $aSave["status"]    = "1";
            // $aSave["hotel_id"]      = $aData["hotel_id"];
            $aSave["create_date"]   = date("Y-m-d H:i:s");
            $aSave["create_by"]     = $aData["user"];
            $aSave["update_date"]   = date("Y-m-d H:i:s");
            $aSave["update_by"]     = $aData["user"];

            if ($this->db->replace('m_status_hotel', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        } else {
            $aSave["status"]                = $aData["txtHotelStatus_status"];
            $aSave["update_date"]           = date("Y-m-d H:i:s");
            $aSave["update_by"]             = $aData["user"];
            $this->db->where("id", $aData["txtHotelStatus_id"] );
            if ($this->db->update('m_status_hotel', $aSave)) {
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
        $this->db->where("id", $aData["hotelstatus_id"] );
        if ($this->db->update('m_status_hotel', $aSave)) {
            $aReturn["flag"] = true;
            $aReturn["msg"] = "success";
        }else{
            $aReturn["flag"] = false;
            $aReturn["msg"] = "Error SQL !!!";
        }

        return $aReturn;
    }
}