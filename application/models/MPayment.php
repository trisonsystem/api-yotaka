<?php
class MPayment extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function search_payment( $aData ){
		// debug($aData, true);
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
		if ( !isset($aData["booking_id"]) ) 	{ $aData["booking_id"] 		= "";}

		$LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

		$WHERE   = "";
		$WHERE  .= ( $aData["payment_id"] 	== "" ) ? "" : " AND P.id='".$aData["payment_id"]."'";
		$WHERE  .= ( $aData["status"] 		== "" ) ? "" : " AND P.status = '".$aData["status"]."'";
		$WHERE  .= ( $aData["bank_transfer_form"] == "" ) ? "" : " AND P.m_bank_id_transfer = '".$aData["bank_transfer_form"]."'";
		$WHERE  .= ( $aData["bank_transfer_to"] 	== "" ) ? "" : " AND P.m_bank_number_list_id = '".$aData["bank_transfer_to"]."'";
		$WHERE  .= ( $aData["slPaymentType"] 		== "" ) ? "" : " AND P.pay_type ='".$aData["slPaymentType"]."'";
		$WHERE  .= ( $aData["payment_time"] 		== "" ) ? "" : " AND P.pay_time LIKE '".$this->convert_date_to_base($aData["payment_time"])."%'";
		$WHERE  .= ( $aData["booking_id"] 	== "" ) ? "" : " AND P.booking_id='".$aData["booking_id"]."'";
		$bank_name = ",B.name_".$aData["lang"]." AS bank_name";
		$WHERE  .= " AND P.hotel_id ='".$aData["hotel_id"]."'";

		$sql 	= "	SELECT P.*, C.prefix, C.name, C.last_name,BL.account_number,B.name_th AS bank_name, 
					-- BK.room_qty AS bok_room_qty, BK.customer_qty AS bok_customer_qty, BK.child_qty AS bok_child_qty, 
					-- BK.check_in AS bok_check_in, BK.check_out AS bok_check_out, BK.prefix_book AS bok_prefix_book, 
					BK.name_book AS bok_name_book, BK.lastname_book AS bok_lastname_book, BK.status AS bok_status
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
		// echo $WHERE;
		return $arr;
	}

	public function search_booking( $aData ){
		if ( !isset($aData["booking_id"]) )        { $aData["booking_id"]     = "";}
		if ( !isset($aData["is_waitpayment"]) )        { $aData["is_waitpayment"]     = "";}

		$WHERE   = "";
		$WHERE  .= ( $aData["booking_id"] 	== "" ) ? "" : " AND BK.id='".$aData["booking_id"]."'";
		$WHERE  .= ( $aData["is_waitpayment"]      == "" ) ? "" : " AND BK.status='wait_payment'";
		$WHERE 	.= " AND BK.status IN('wait_payment', 'outstanding') GROUP BY BL.m_room_id";

		$sql = "SELECT BK.*, RO.code AS room_code, RO.name AS room_name, RO.price AS room_price, RT.name AS room_type, RT.id AS room_typeid, C.profile_img
				FROM booking AS BK
				LEFT JOIN m_customer AS C ON BK.m_customer_id_book = C.id
				LEFT JOIN booking_room_list AS BL ON BK.id = BL.booking_id
				LEFT JOIN m_room AS RO ON BL.m_room_id = RO.id
				LEFT JOIN m_room_type AS RT ON RO.m_room_type_id = RT.id
				WHERE 1 = 1  $WHERE ";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;

		}

		return $arr;
	}

	public function search_booking_cusprofile( $aData ){
		if ( !isset($aData["book_id"]) )        { $aData["book_id"]     = "";}

		$WHERE   = "";
		$WHERE  .= ( $aData["book_id"] 	== "" ) ? "" : " AND BK.id='".$aData["book_id"]."'";

		$sql = "SELECT BK.*, C.profile_img
				FROM booking AS BK
				LEFT JOIN m_customer AS C ON BK.m_customer_id_book = C.id
				WHERE 1 = 1 $WHERE AND BK.status IN('wait_payment', 'outstanding')";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		return $arr;
	}

	public function search_booking_cusprofile_notin( $aData ){
		if ( !isset($aData["book_id"]) )        { $aData["book_id"]     = "";}

		$WHERE   = "";
		$WHERE  .= ( $aData["book_id"] 	== "" ) ? "" : " AND BK.id='".$aData["book_id"]."'";

		$sql = "SELECT BK.*, C.profile_img
				FROM booking AS BK
				LEFT JOIN m_customer AS C ON BK.m_customer_id_book = C.id";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		return $arr;
	}

	public function search_payment_status( $aData ){
		$arr   = array();
		$arr[] = array('id'=>'wait_confirm' ,'name' => 'wait_confirm');
		$arr[] = array('id'=>'already_paid' ,'name' => 'already_paid');
		$arr[] = array('id'=>'outstanding' ,'name' => 'outstanding');
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

	public function save_data( $aData ){
		$aReturn = array();
		$this->check_param( $aData, $aData["eslPaytype"]);

		$fodel    = "assets/upload/promotion_images/";
        $aFN      = explode(".", $aData["txtImages"]);
        $n_name   = $aFN[count($aFN)-1];

        if ($aData["txtPayment_id"] == "0") {
            $code = $aData["etxtPayment_m_customer_id_book"];
        }else{
            $arrStr = explode("/", $aFN[0]);
            
            if ($aData["oldImages"] != $arrStr[3]) {
                $code = $aData["etxtPayment_m_customer_id_book"]."(1)";
            }else{
                $code = $aData["oldImages"];
            }
        }

        $n_path   = $fodel.$code.".".$n_name;

		$aSave   = array();
        $aSave["booking_id"]  = $aData["etxtPayment_booking_id"];
        $aSave["m_customer_id"]  = $aData["etxtPayment_m_customer_id_book"];        
        $aSave["summary"]  = $aData["etxtPayment_totroomprice"];        
        $aSave["discount"]  = $aData["etxtPayment_discount"];
        $aSave["total"]  = $aData["etxtPayment_total"];
        $aSave["pay_amount"]  = $aData["etxtPaymentAmount"];        
        $aSave["promotion_id"]  = $aData["etxtPayment_promotion_id"];        
        $aSave["pay_type"]  = $aData["eslPaytype"];

        $aSave["m_bank_id_transfer"]  = $aData["eslPaymentType"];
        $aSave["m_bank_number_list_id"]  = $aData["eslBankTransferTo"];        
        $aSave["transfer_date"]  = date("d-m-Y", strtotime($aData["txtPaymentDateTime"]));
        $ttime = $aData["timeHour"] . ":" . $aData["timeMinute"];
        $aSave["transfer_time"]  = date("H:i:s", strtotime($ttime));

        if($aData["txtImages"] != "0"){
        	$aSave["transfer_img"]  = $n_path;
        }
        
        $cardData = array(
        	'card_id' => $aData["etxtPayment_cardcode"],
        	'card_name' => $aData["etxtPayment_cardname"],
        	'card_expireddate' => $aData["etxtPayment_cardexpireddate"],
        	'card_cvv' => $aData["etxtPayment_cardevv"]
        );
        $aSave["pay_card"]  = json_encode($cardData);

        $aSave["remark"]  = $aData["etxtPaymentDescription"];
        $aSave["pay_time"]  = date("Y-m-d H:i:s");

        if($aSave["total"] == $aSave["pay_amount"]){
    		$aSave["status"]    		= "already_paid";
		}else{
			$aSave["status"]    		= "outstanding";
		}
        
        if ($aData['txtPayment_id'] == "0") {
            $aSave["hotel_id"]      = $aData["hotel_id"];
            $aSave["create_date"]   = date("Y-m-d H:i:s");
            $aSave["create_by"]     = $aData["user"];
            $aSave["update_date"]   = date("Y-m-d H:i:s");
            $aSave["update_by"]     = $aData["user"];

            if ($this->db->replace('payment', $aSave)) {
            	$aReturn["flag"] = true;
                $aReturn["msg"] = "success";
                $aReturn["code"] = $code;
                // debug($aReturn, true);
            	if($aSave["total"] == $aSave["pay_amount"]){
	        		$bSave["status"]    		= "already_paid";
        		}else{
        			$bSave["status"]    		= "outstanding";
        		}
        		$bSave["update_date"]           = date("Y-m-d H:i:s");
	            $bSave["update_by"]             = $aData["user"];
        		$this->db->where("id", $aData["etxtPayment_booking_id"] );
        		$this->db->update('booking', $bSave);      
        		// return $aReturn;          
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        } else {
            $aSave["status"]    = $aData["txtPosition_status"];
            $aSave["update_date"]           = date("Y-m-d H:i:s");
            $aSave["update_by"]             = $aData["user"];
            $this->db->where("id", $aData["txtPayment_id"] );
            if ($this->db->update('payment', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        }
       
        return $aReturn;

	}

	public function check_param( $aData, $useCase ){
		$arrParam = array();

		switch ($useCase) {
			case 'transfer':
				$arrParam = array('hotel_id', 'txtPayment_id', 'etxtPayment_booking_id', 'etxtPayment_m_customer_id_book', 'etxtPayment_m_customer_id_guest', 'etxtPayment_promotion_id', 'etxtPayment_check_in', 'etxtPayment_check_out', 'etxtPayment_name_book', 'etxtPayment_lastname_book', 'etxtPayment_name_guest', 'etxtPayment_lastname_guest', 'eslPaytype', 'etxtPayment_total', 'etxtPaymentAmount', 'etxtPayment_discount', 'etxtPayment_totroomprice', 'eslPaymentType', 'eslBankTransferTo');
				break;
			
			case 'pay_cash':
				$arrParam = array('hotel_id', 'txtPayment_id', 'etxtPayment_booking_id', 'etxtPayment_m_customer_id_book', 'etxtPayment_m_customer_id_guest', 'etxtPayment_promotion_id', 'etxtPayment_check_in', 'etxtPayment_check_out', 'etxtPayment_name_book', 'etxtPayment_lastname_book', 'etxtPayment_name_guest', 'etxtPayment_lastname_guest', 'eslPaytype', 'etxtPayment_total', 'etxtPaymentAmount', 'etxtPayment_discount', 'etxtPayment_totroomprice');
				break;

			case 'visa':
				# code...
				break;

			case 'wallet':
				# code...
				break;
		}

        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }
	}
}