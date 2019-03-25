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
        if ( !isset($aData["room_type_id"]) )       { $aData["room_type_id"]    = "";}

        $LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["promotion_id"]      == "" ) ? "" : " AND PM.id='".$aData["promotion_id"]."'";
        $WHERE  .= ( $aData["promotion_code"] 		== "" ) ? "" : " AND PM.promotion_code LIKE '%".$aData["promotion_code"]."%'";
        $WHERE  .= ( $aData["promotion_title"] 		== "" ) ? "" : " AND PM.title LIKE '%".$aData["promotion_title"]."%'";
        $WHERE  .= ( $aData["promotion_status"] 	== "" ) ? "" : " AND PM.status='".$aData["promotion_status"]."'";
        $WHERE  .= ( $aData["room_type_id"]     == "" ) ? "" : " AND PML.m_room_type_id='".$aData["room_type_id"]."'";
        $WHERE  .= " AND PM.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT PM.*, PML.m_room_type_id, PML.discount, RT.NAME AS room_type_name, PML.m_room_type_id AS room_type_id
                FROM promotion AS PM 
                RIGHT JOIN promotion_list AS PML ON PM.id = PML.promotion_id
                LEFT JOIN m_room_type AS RT ON PML.m_room_type_id = RT.id
                WHERE 1 = 1 $WHERE
                ORDER BY PM.id DESC LIMIT $LIMIT";

        $query 	= $this->db->query($sql);
        $arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		$arr["limit"] = $lm;
		// debug($arr);
        // echo $sql;
		return $arr;
    }

    public function search_promotion_codeanddate( $aData ){
        $aReturn = array();
        $arrParam = array('check_in', 'check_out', 'promotion_code', 'hotel_id');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }

        $WHERE = "";
        $WHERE .= ( $aData["check_in"]      == "" ) ? "" : " AND PM.startdate <= '".$aData['check_in']."'";
        $WHERE .= ( $aData["check_out"]      == "" ) ? "" : " AND PM.enddate >= '".$aData['check_out']."'";
        $WHERE .= ( $aData["promotion_code"]      == "" ) ? "" : " AND PM.promotion_code <= '".$aData['promotion_code']."'";
        $WHERE .= " AND status=1";
        $WHERE  .= " AND PM.hotel_id='".$aData["hotel_id"]."'";

        $sql = "SELECT *
                FROM promotion AS PM
                WHERE 1 = 1 $WHERE
                ORDER BY PM.id DESC";

        $query  = $this->db->query($sql);
        $arr = array();
        foreach ($query->result_array() as $key => $value) {
            $arr[] = $value;
        }
        // echo $sql;
        // debug($arr);
        return $arr;
    }

    public function save_data( $aData ){
    	$aReturn = array();
        $arrParam = array('txtPromotion_id', 'etxtPromotionTitle', 'etxtPromotionCode', 'etxtPromotionDescription', 'from_date', 'to_date', 'txtPromotionImages', 'txtPromotion_status', 'hotel_id', 'rroomType_id', 'rroomType_value');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }

        $fodel    = "assets/upload/promotion_images/";
        $aFN      = explode(".", $aData["txtPromotionImages"]);
        $n_name   = $aFN[count($aFN)-1];

        if ($aData["txtPromotion_id"] == "0") {
            $code = $aData["etxtPromotionCode"];
        }else{
            $arrStr = explode("/", $aFN[0]);
            
            if ($aData["oldPromotionImages"] != $arrStr[3]) {
                $code = $aData["etxtPromotionCode"]."(1)";
            }else{
                $code = $aData["oldPromotionImages"];
            }
        }

        $n_path   = $fodel.$code.".".$n_name;
        
        $aSave   = array();
        $aSave["title"]  = $aData["etxtPromotionTitle"];
        $aSave["promotion_code"]  = $aData["etxtPromotionCode"];
        $aSave["description"]  = $aData["etxtPromotionDescription"];
        $aSave["startdate"]  = $aData["from_date"];
        $aSave["enddate"]  = $aData["to_date"];
        // $aSave["discount"]  = $aData["etxtPromotionPrice"];

        if ($aData["txtPromotionImages"] != "0") {
            $aSave["promotion_img"]       = $n_path;
        }
        
        if ($aData['txtPromotion_id'] == "0") {
            $aSave["status"]    = "1";
            $aSave["hotel_id"]      = $aData["hotel_id"];
            $aSave["create_date"]   = date("Y-m-d H:i:s");
            $aSave["create_by"]     = $aData["user"];
            $aSave["update_date"]   = date("Y-m-d H:i:s");
            $aSave["update_by"]     = $aData["user"];

            if ($this->db->insert('promotion', $aSave)) {
                $insert_id = $this->db->insert_id();
                $bSave  = array();
                $rroomType_id = explode(",", $aData["rroomType_id"]);
                $rroomType_value = explode(",", $aData["rroomType_value"]);
                if(count($rroomType_id) == count($rroomType_value)){
                    for ($i=0; $i < count($rroomType_id); $i++) { 
                        $bSave['promotion_id'] = $insert_id;
                        $bSave['m_room_type_id'] = $rroomType_id[$i];
                        $bSave['discount'] = $rroomType_value[$i];
                        $this->db->replace('promotion_list', $bSave);
                    }
                }

                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
                $aReturn["code"] = $code;
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        } else {
            $aSave["status"]    = $aData["txtPromotion_status"];
            $aSave["update_date"]           = date("Y-m-d H:i:s");
            $aSave["update_by"]             = $aData["user"];
            
            $sql = "DELETE FROM promotion_list WHERE promotion_id=".$aData["txtPromotion_id"]." AND m_room_type_id IN (".$aData["rroomType_id"].")";
                $query = $this->db->query($sql);
            $rroomType_id = explode(",", $aData["rroomType_id"]);
            if(count($rroomType_id) > 1){
                $rroomType_value = explode(",", $aData["rroomType_value"]);
                if(count($rroomType_id) == count($rroomType_value)){
                    for ($i=0; $i < count($rroomType_id); $i++) { 
                        $bSave['promotion_id'] = $aData["txtPromotion_id"];
                        $bSave['m_room_type_id'] = $rroomType_id[$i];
                        $bSave['discount'] = $rroomType_value[$i];
                        $this->db->replace('promotion_list', $bSave);
                    }
                }
            }else{
                $bSave['promotion_id'] = $aData["txtPromotion_id"];
                $bSave['m_room_type_id'] = $aData["rroomType_id"];
                $bSave['discount'] = $aData["rroomType_value"];
                $this->db->replace('promotion_list', $bSave);
            }
            
            $this->db->where("id", $aData["txtPromotion_id"] );
            if ($this->db->update('promotion', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
                $aReturn["code"] = $code;
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
        $this->db->where("id", $aData["promotion_id"] );
        if ($this->db->update('promotion', $aSave)) {
            $aReturn["flag"] = true;
            $aReturn["msg"] = "success";
        }else{
            $aReturn["flag"] = false;
            $aReturn["msg"] = "Error SQL !!!";
        }

        return $aReturn;
    }
}