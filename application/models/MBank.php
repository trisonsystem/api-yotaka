<?php
class MBank extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function search_bank( $aData ){
		$arrParam = array('hotel_id');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }
		$WHERE   = "";
		$WHERE  .= " AND BK.hotel_id='".$aData["hotel_id"]."'";

		$sql 	= "	SELECT BK.*
					FROM m_bank_number_list AS BK
					WHERE 1 = 1 $WHERE
					ORDER BY BK.id DESC";
		$query 	= $this->db->query($sql);
		
		$arr = array();
		foreach ($query->result_array() as $key => $value) {
			$arr[] = $value;
		}
		// debug($arr);
		return $arr;
	}
}
?>