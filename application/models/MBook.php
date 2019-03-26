<?php
class MBook extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function search_room_forbook( $aData ){
		$arrParam = array('txtCheckIn','txtCheckOut','txtGuestsQty','slChildenBook','slRoom');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }
		if ( !isset($aData["txtCheckIn"]) ) 	{ $aData["txtCheckIn"] 			= "";}
		if ( !isset($aData["txtCheckOut"]) ) 	{ $aData["txtCheckOut"] 		= "";}
		if ( !isset($aData["txtGuestsQty"]) ) 	{ $aData["txtGuestsQty"] 		= "";}
		if ( !isset($aData["room_name"]) ) 		{ $aData["room_name"] 			= "";}
		if ( !isset($aData["status"]) ) 		{ $aData["status"] 				= "";}

		$aData["txtCheckIn"] 	= $this->convert_date_to_base( $aData["txtCheckIn"] );
		$aData["txtCheckOut"] 	= $this->convert_date_to_base( $aData["txtCheckOut"] );

		$WHERE   = "";
		$WHERE  .= ( $aData["txtCheckIn"] == "" && $aData["txtCheckOut"] == "" ) ? "" : " AND ( BKL.book_time NOT BETWEEN '".$aData["txtCheckIn"]."' AND '".$aData["txtCheckOut"]."'  OR BKL.book_time IS NULL )";
		// $WHERE  .= ( $aData["txtGuestsQty"] 	== "" ) ? "" : " AND R.name LIKE '%".$aData["txtGuestsQty"]."%'";
		// $WHERE  .= ( $aData["slRoom"] 			== "" ) ? "" : " AND R.status='".$aData["slRoom"]."'";
		$WHERE  .= " AND R.hotel_id='".$aData["hotel_id"]."'";

		$sql 	= " SELECT R.*, BKL.book_time,  TR.name AS type_room_name
					FROM m_room AS R
					LEFT JOIN booking_room_list AS BKL ON R.id = BKL.m_room_id
					LEFT JOIN m_room_type AS TR ON R.m_room_type_id = TR.id
					WHERE 1 = 1 AND R.status = 'open_status'  $WHERE
					GROUP BY R.id
					ORDER BY R.m_room_type_id ASC";
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
		$arrParam = array('hotel_id','user','GuestsQty','ChildenQty','CheckIn','CheckOut','CustomerID','Prefix','Name','LastName','Tel','Email','BookCustomerID','BookPrefix','BookName','BookLastName','BookTel','BookEmail','Sum_price','BookID','room');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }

        $aSave 	 = array();
		$aSave["hotel_id"] 			= $aData["hotel_id"];
		$aSave["customer_qty"] 		= $aData["GuestsQty"];
		$aSave["child_qty"] 		= $aData["ChildenQty"];
		$aSave["check_in"] 			= $this->convert_date_to_base( $aData["CheckIn"] );
		$aSave["check_out"] 		= $this->convert_date_to_base( $aData["CheckOut"] );
		$aSave["m_customer_id_book"]= ($aData["rBook"] == "main")? $aData["CustomerID"] : $aData["BookCustomerID"];
		$aSave["prefix_book"] 		= ($aData["rBook"] == "main")? $aData["Prefix"] 	: $aData["BookPrefix"];
		$aSave["name_book"] 		= ($aData["rBook"] == "main")? $aData["Name"] 		: $aData["BookName"];
		$aSave["lastname_book"] 	= ($aData["rBook"] == "main")? $aData["LastName"] 	: $aData["BookLastName"];
		$aSave["tel_book"] 			= ($aData["rBook"] == "main")? $aData["Tel"] 		: $aData["BookTel"];
		$aSave["email_book"] 		= ($aData["rBook"] == "main")? $aData["Email"] 		: $aData["BookEmail"];
		$aSave["m_customer_id_guest"] = $aData["CustomerID"];
		$aSave["prefix_guest"] 		= $aData["Prefix"];
		$aSave["name_guest"] 		= $aData["Name"];
		$aSave["lastname_guest"] 	= $aData["LastName"];
		$aSave["email_guest"] 		= $aData["Email"];
		$aSave["tel_guest"] 		= $aData["Tel"];
		$aSave["summary"] 			= $aData["Sum_price"];
		$aSave["room_qty	"] 		= $aData["RoomQty"];
		if ($aData["ChildenQty"] > 0) {
			$cd = "";
			foreach ($aData["Childen"] as $key => $value) {
				$cd .= ",".$value;
			}
			$aSave["child_age"] = substr( $cd, 1);
		}
// debug($aSave,true);
		$booking_id = $aData["BookID"];
		if ($aData["BookID"] == "0") {
			$aSave["status"] 				= 'wait_payment';
			$aSave["create_date"] 			= date("Y-m-d H:i:s");
			$aSave["create_by"] 			= $aData["user"];
			$aSave["update_date"] 			= date("Y-m-d H:i:s");
			$aSave["update_by"] 			= $aData["user"];

			if ($this->db->insert('booking', $aSave)) {
				$aReturn["flag"] = true;
				$aReturn["msg"] = "success";
				$booking_id  	= $this->db->insert_id();
			}else{
				$aReturn["flag"] = false;
				$aReturn["msg"] = "Error SQL !!!";
			}
		}else{
			$aSave["update_date"] 			= date("Y-m-d H:i:s");
			$aSave["update_by"] 			= $aData["user"];
			$this->db->where("id", $aData["BookID"] );
			if ($this->db->update('booking', $aSave)) {
				$aReturn["flag"] = true;
				$aReturn["msg"] = "success";
			}else{
				$aReturn["flag"] = false;
				$aReturn["msg"] = "Error SQL !!!";
			}
		}

		$c_date = $this->DateDiff($aData["CheckIn"] , $aData["CheckOut"]) + 1;
		foreach ($aData["room"] as $key => $value) {
			for ($i=0; $i < $c_date; $i++) {
				$book_time = date ("Y-m-d", strtotime("+".$i." day", strtotime($aData["CheckIn"]))); 

				$aSave = array();
				$aSave["hotel_id"] 				= $aData["hotel_id"];
				$aSave["m_room_id"] 			= $value;
				$aSave["booking_id"] 			= $booking_id;
				$aSave["book_time"] 			= $book_time;
				$aSave["status"] 				= 1;
				$aSave["create_date"] 			= date("Y-m-d H:i:s");
				$aSave["create_by"] 			= $aData["user"];
				$aSave["update_date"] 			= date("Y-m-d H:i:s");
				$aSave["update_by"] 			= $aData["user"];

				$this->db->insert('booking_room_list', $aSave);
			}
		}

		// debug($aSave);

		return $aReturn;

	}

	function convert_date_to_base($str_date){
		if ($str_date != "") {
			$aDate = explode("-", $str_date);
			return $aDate["2"]."-".$aDate["1"]."-".$aDate["0"];
		}
	}

	public function DateDiff($strDate1,$strDate2){
		return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
	}

	public function search_status_book( $aData ){
		$arr   = array();
		$arr[] = array('id'=>'wait_payment' ,'name' => 's_wait_payment');
		$arr[] = array('id'=>'already_paid' ,'name' => 's_already_paid');
		$arr[] = array('id'=>'outstanding'  ,'name' => 's_outstanding');
		$arr[] = array('id'=>'check_in'  ,'name' => 's_check_in');
		$arr[] = array('id'=>'check_out' ,'name' => 's_check_out');
		$arr[] = array('id'=>'cancel'   ,'name' => 's_cancel');

		return $arr;
	}

	public function search_book_list( $aData ){
		$lm = 15;
		if ( !isset($aData["page"]) ) 		 	{ $aData["page"] 				= 1;}
		if ( !isset($aData["book_id"]) ) 		{ $aData["book_id"] 			= "";}
		if ( !isset($aData["check_in"]) ) 		{ $aData["check_in"] 			= "";}
		if ( !isset($aData["check_out"]) ) 		{ $aData["check_out"] 			= "";}
		if ( !isset($aData["name_book"]) ) 		{ $aData["name_book"] 			= "";}
		if ( !isset($aData["last_name_book"]) )	{ $aData["last_name_book"] 		= "";}
		if ( !isset($aData["book_status"]) )	{ $aData["book_status"] 		= "";}
		$aData["check_in"] 	= $this->convert_date_to_base( $aData["check_in"] );
		$aData["check_out"] = $this->convert_date_to_base( $aData["check_out"] );
		$LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

		$WHERE   = "";
		$WHERE  .= ( $aData["book_id"] 			== "" ) ? "" : " AND BK.id='".$aData["book_id"]."'";
		$WHERE  .= ( $aData["check_in"] == "" && $aData["check_out"] == "" ) ? "" : " AND BKL.book_time BETWEEN '".$aData["check_in"]."' AND '".$aData["check_out"]."'";
		$WHERE  .= ( $aData["name_book"]		== "" ) ? "" : " AND BK.name_book LIKE '%".$aData["name_book"]."%'";
		$WHERE  .= ( $aData["last_name_book"] 	== "" ) ? "" : " AND BK.lastname_book='".$aData["last_name_book"]."'";
		$WHERE  .= ( $aData["book_status"] 		== "" ) ? "" : " AND BK.status='".$aData["book_status"]."'";
		$WHERE  .= " AND BK.hotel_id='".$aData["hotel_id"]."'";

		$sql 	= "	SELECT BK.*
					FROM booking AS BK
					LEFT JOIN booking_room_list AS BKL ON BK.id = BKL.booking_id
					WHERE 1 = 1 $WHERE
					GROUP BY BK.id
					ORDER BY BK.id DESC
					LIMIT $LIMIT";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		$arr["limit"] = $lm;
		// debug($arr);
		return $arr;
	}

	public function chang_status( $aData ){
		$aSave["update_date"]   = date("Y-m-d H:i:s");
        $aSave["update_by"]     = $aData["user"];
        $aSave["status"]    	= $aData["status"];
        $this->db->where("id", $aData["book_id"] );
        if ($this->db->update('booking', $aSave)) {
            $aReturn["flag"] = true;
            $aReturn["msg"] = "success";
        }else{
            $aReturn["flag"] = false;
            $aReturn["msg"] = "Error SQL !!!";
        }

        return $aReturn;
	}
}