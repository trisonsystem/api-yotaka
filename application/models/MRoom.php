<?php
class MRoom extends CI_Model {

	public function __construct(){
		parent::__construct();

	}
	
	public function search_room( $aData ){
		$lm = 15;
		if ( !isset($aData["page"]) ) 		 	{ $aData["page"] 				= 1;}
		if ( !isset($aData["room_id"]) ) 		{ $aData["room_id"] 			= "";}
		if ( !isset($aData["room_code"]) ) 		{ $aData["room_code"] 			= "";}
		if ( !isset($aData["room_name"]) ) 		{ $aData["room_name"] 			= "";}
		if ( !isset($aData["status"]) ) 		{ $aData["status"] 				= "";}
		if ( !isset($aData["room_type_id"]) ) 	{ $aData["room_type_id"] 		= "";}
		if ( !isset($aData["amphur_id"]) ) 		{ $aData["amphur_id"] 			= "";}
		if ( !isset($aData["hotel_id"]) ) 		{ $aData["hotel_id"] 			= "";}

		$LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

		$WHERE   = "";
		$WHERE  .= ( $aData["room_id"] 			== "" ) ? "" : " AND R.id='".$aData["room_id"]."'";
		$WHERE  .= ( $aData["room_code"] 		== "" ) ? "" : " AND R.code LIKE '%".$aData["room_code"]."%'";
		$WHERE  .= ( $aData["room_name"] 		== "" ) ? "" : " AND R.name LIKE '%".$aData["room_name"]."%'";
		$WHERE  .= ( $aData["status"] 			== "" ) ? "" : " AND R.status='".$aData["status"]."'";
		$WHERE  .= ( $aData["room_type_id"] 	== "" ) ? "" : " AND R.m_room_type_id='".$aData["room_type_id"]."'";
		$WHERE  .= " AND R.hotel_id='".$aData["hotel_id"]."'";

		$sql 	= " SELECT  R.* , TR.name AS type_room_name
					FROM m_room AS R 
					LEFT JOIN m_room_type AS TR ON R.m_room_type_id = TR.id
					WHERE 1 = 1  $WHERE ORDER BY R.id ASC LIMIT $LIMIT";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		$arr["limit"] = $lm;
		// debug($arr);
		return $arr;
	}

	public function search_type_room( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  = ( !isset( $aData["type_room_id"] ) ) ? "" : " AND id='".$aData["type_room_id"]."'";
			$WHERE  .= " AND hotel_id='".$aData["hotel_id"]."'";
		}
		$sql 	= " SELECT  * FROM m_room_type WHERE 1 = 1  $WHERE ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_room_item( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  = ( !isset( $aData["room_item_id"] ) ) ? "" : " AND id='".$aData["room_item_id"]."'";
			$WHERE  .= " AND hotel_id='".$aData["hotel_id"]."'";
		}
		$sql 	= " SELECT  * FROM m_room_item WHERE 1 = 1  $WHERE ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function search_room_item_list( $aData ){
		$WHERE  = "";
		if ($aData != "") {
			$WHERE  = ( !isset( $aData["room_item_id"] ) ) ? "" : " AND id='".$aData["room_item_id"]."'";
			$WHERE  = ( !isset( $aData["room_id"] ) ) ? "" : " AND m_room_id='".$aData["room_id"]."'";
			$WHERE  .= " AND hotel_id='".$aData["hotel_id"]."'";
		}
		$sql 	= " SELECT  * FROM m_room_item_list WHERE 1 = 1  $WHERE ORDER BY id ASC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}

		// debug($arr);
		return $arr;
	}

	public function save_data( $aData ){
		$aReturn = array();
        $aSave 	 = array();

        $arrParam = array('txtCode','txtName','hotel_id','slRoomType','txtPrice','txtRemark','txtRoom_id');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }

        $room_id = $aData["txtRoom_id"];
		$aSave["code"] 				= $aData["txtCode"];
		$aSave["name"] 				= $aData["txtName"];
		$aSave["hotel_id"] 			= $aData["hotel_id"];
		$aSave["m_room_type_id"] 	= $aData["slRoomType"];
		$aSave["price"] 			= $aData["txtPrice"];
		$aSave["qty_people"] 		= $aData["txtQtyPeople"];
		$aSave["remark"] 			= $aData["txtRemark"];
		if ($aData["txtRoom_id"] == "0") {
			$aSave["create_date"] 			= date("Y-m-d H:i:s");
			$aSave["create_by"] 			= $aData["user"];
			$aSave["update_date"] 			= date("Y-m-d H:i:s");
			$aSave["update_by"] 			= $aData["user"];

			$aSave["status"] 				= "close_status";

			if ($this->db->insert('m_room', $aSave)) {
				$aReturn["flag"] = true;
				$aReturn["msg"] = "success";

				$room_id = $this->db->insert_id();
			}else{
				$aReturn["flag"] = false;
				$aReturn["msg"] = "Error SQL !!!";
			}
		}else{
			$aSave["update_date"] 			= date("Y-m-d H:i:s");
			$aSave["update_by"] 			= $aData["user"];
			$this->db->where("id", $aData["txtRoom_id"] );
			if ($this->db->update('m_room', $aSave)) {
				$aReturn["flag"] = true;
				$aReturn["msg"] = "success";
			}else{
				$aReturn["flag"] = false;
				$aReturn["msg"] = "Error SQL !!!";
			}
		}

		if ($aReturn["flag"]) {
			if ($aData["txtRoom_id"] != "0") {
				$this->db->where("hotel_id", $aData["hotel_id"] );
				$this->db->where("m_room_id" , $room_id );
				$this->db->delete('m_room_item_list');
			}

			for ($i=1; $i <= $aData["txtCountItem"]; $i++) {
				if (isset($aData["slItem_".$i])) {
					$aSave 	 = array();
					$aSave["m_room_item_id"] 		= $aData["slItem_".$i];
					$aSave["qty"] 					= $aData["txtQty_".$i];
					$aSave["m_room_id"] 			= $room_id;
					$aSave["hotel_id"] 				= $aData["hotel_id"];
					$aSave["status"] 				= "1";
					$aSave["create_date"] 			= date("Y-m-d H:i:s");
					$aSave["create_by"] 			= $aData["user"];
					$aSave["update_date"] 			= date("Y-m-d H:i:s");
					$aSave["update_by"] 			= $aData["user"];
					$this->db->insert('m_room_item_list', $aSave);
				}
			}
			
		}

		
		// debug($aSave);

		return $aReturn;

	}

	public function chang_status( $aData ){
		$aSave["update_date"] 			= date("Y-m-d H:i:s");
		$aSave["update_by"] 			= $aData["user"];
		$aSave["status"] 				= $aData["status"];
		$this->db->where("id", $aData["room_id"] );
		if ($this->db->update('m_room', $aSave)) {
			$aReturn["flag"] = true;
			$aReturn["msg"] = "success";
		}else{
			$aReturn["flag"] = false;
			$aReturn["msg"] = "Error SQL !!!";
		}

		return $aReturn;
	}


	
}