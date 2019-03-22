<?php
class MPayment extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function search_payment( $aData ){
		$lm = 15;
		if ( !isset($aData["page"]) ) 		 	{ $aData["page"] 				= 1;}
		if ( !isset($aData["bank_transfer_form"]) ){ $aData["bank_transfer_form"] = "";}
		if ( !isset($aData["bank_transfer_to"]) )  { $aData["bank_transfer_to"]   = "";}
		if ( !isset($aData["status"]) ) 		{ $aData["status"] 				= "";}
		if ( !isset($aData["payment_id"]) ) 	{ $aData["payment_id"] 			= "";}
		if ( !isset($aData["hotel_id"]) ) 		{ $aData["hotel_id"] 			= "";}
		if ( !isset($aData["lang"]) ) 			{ $aData["lang"] 				= "en";}
		if ( !isset($aData["slPaymentType"]) ) 	{ $aData["slPaymentType"] 		= "";}
		if ( !isset($aData["payment_time"]) ) 	{ $aData["payment_time"] 		= "";}

		$LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

		$WHERE   = "";
		$WHERE  .= ( $aData["payment_id"] 	== "" ) ? "" : " AND P.id='".$aData["payment_id"]."'";
		$WHERE  .= ( $aData["status"] 		== "" ) ? "" : " AND P.status = '".$aData["status"]."'";
		$WHERE  .= ( $aData["bank_transfer_form"] == "" ) ? "" : " AND P.m_bank_id_transfer = '".$aData["bank_transfer_form"]."'";
		$WHERE  .= ( $aData["bank_transfer_to"] 	== "" ) ? "" : " AND P.m_bank_number_list_id = '".$aData["bank_transfer_to"]."'";
		$WHERE  .= ( $aData["slPaymentType"] 		== "" ) ? "" : " AND P.pay_type ='".$aData["slPaymentType"]."'";
		$WHERE  .= ( $aData["payment_time"] 		== "" ) ? "" : " AND P.pay_time LIKE '".$this->convert_date_to_base($aData["payment_time"])."%'";
		$bank_name = ",B.name_".$aData["lang"]." AS bank_name";
		$WHERE  .= " AND P.hotel_id ='".$aData["hotel_id"]."'";

		$sql 	= "	SELECT P.*, C.prefix, C.name, C.last_name,BL.account_number,B.name_th AS bank_name, 
					-- BK.room_qty AS bok_room_qty, BK.customer_qty AS bok_customer_qty, BK.child_qty AS bok_child_qty, 
					-- BK.check_in AS bok_check_in, BK.check_out AS bok_check_out, BK.prefix_book AS bok_prefix_book, 
					BK.name_book AS bok_name_book, BK.lastname_book AS bok_lastname_book
					FROM payment AS P
					LEFT JOIN m_customer AS C ON P.m_customer_id = C.id
					LEFT JOIN m_bank_number_list AS BL ON P.m_bank_number_list_id = BL.id
					LEFT JOIN m_bank AS B ON BL.m_bank_id = B.id
					LEFT JOIN booking AS BK ON P.booking_id = BK.id
					WHERE 1 = 1  $WHERE 
					ORDER BY P.id DESC
					LIMIT $LIMIT";
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

	public function search_booking( $aData ){
		if ( !isset($aData["booking_id"]) )        { $aData["booking_id"]     = "";}
		if ( !isset($aData["is_waitpayment"]) )        { $aData["is_waitpayment"]     = "";}

		$WHERE   = "";
		$WHERE  .= ( $aData["booking_id"] 	== "" ) ? "" : " AND BK.id='".$aData["booking_id"]."'";
		$WHERE  .= ( $aData["is_waitpayment"]      == "" ) ? "" : " AND BK.status='wait_payment'";

		$sql = "SELECT BK.*, C.profile_img 
				FROM booking AS BK 
				LEFT JOIN m_customer AS C ON BK.m_customer_id_book = C.id
				WHERE 1 = 1  $WHERE ";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;

		}
		// $arr["limit"] = $lm;
		// debug($arr);
		// echo $sql;
		return $arr;
	}

	public function search_payment_status( $aData ){
		$arr   = array();
		$arr[] = array('id'=>'wait_confirm' ,'name' => 'wait_confirm');
		$arr[] = array('id'=>'already_paid' ,'name' => 'already_paid');
		$arr[] = array('id'=>'cancel' ,'name' => 'cancel');

		return $arr;
	}

	public function search_payment_type( $aData ){
		$arr   = array();
		$arr[] = array('id'=>'pay_cash' ,'name' => 'pay_cash');
		$arr[] = array('id'=>'transfer' ,'name' => 'transfer');
		$arr[] = array('id'=>'visa' ,'name' => 'visa');

		return $arr;
	}

	public function convert_date_to_base($str_date){
		if ($str_date != "") {
			$aDate = explode("-", $str_date);
			return $aDate["2"]."-".$aDate["1"]."-".$aDate["0"];
		}
	}

	public function chang_status( $aData ){
		$aSave["update_date"]   = date("Y-m-d H:i:s");
        $aSave["update_by"]     = $aData["user"];
        $aSave["status"]    	= $aData["status"];
        $this->db->where("id", $aData["payment_id"] );
        if ($this->db->update('payment', $aSave)) {
            $aReturn["flag"] = true;
            $aReturn["msg"] = "success";
        }else{
            $aReturn["flag"] = false;
            $aReturn["msg"] = "Error SQL !!!";
        }

        return $aReturn;
	}


}