<?php
class MCustomer extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function search_customer( $aData ){
		$lm = 15;
		if ( !isset($aData["page"]) ) 		 		{ $aData["page"] 				= 1;}
		if ( !isset($aData["customer_id"]) ) 		{ $aData["customer_id"] 		= "";}
		if ( !isset($aData["customer_cardnumber"]) ){ $aData["customer_cardnumber"] = "";}
		if ( !isset($aData["customer_name"]) ) 		{ $aData["customer_name"] 		= "";}
		if ( !isset($aData["customer_lastname"]) ) 	{ $aData["customer_lastname"] 	= "";}

		$LIMIT 	 = ( $aData["page"] 	== "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

		$WHERE   = "";
		$WHERE  .= ( $aData["customer_id"] 		== "" ) ? "" : " AND CT.id='".$aData["customer_id"]."'";
		$WHERE  .= ( $aData["customer_cardnumber"] 	== "" ) ? "" : " AND CT.code LIKE '%".$aData["customer_cardnumber"]."%'";
		$WHERE  .= ( $aData["customer_name"] 	== "" ) ? "" : " AND CT.name LIKE '%".$aData["customer_name"]."%'";
		$WHERE  .= ( $aData["customer_lastname"]== "" ) ? "" : " AND CT.last_name LIKE '%".$aData["customer_lastname"]."%'";
		$WHERE  .= " AND CT.hotel_id='".$aData["hotel_id"]."'";

		$sql 	= "SELECT  CT.*
					FROM m_customer AS CT
					WHERE 1 = 1  $WHERE
					ORDER BY CT.id DESC
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

}