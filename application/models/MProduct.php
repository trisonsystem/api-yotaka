<?php
class MProduct extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function readProduct($arrpost){

		// debug($arrpost,true);

		$limit_s = ($arrpost['page'] > 1)? ($arrpost['page']*$arrpost['limit'])-$arrpost['limit'] : 0;
		$limit_e = $arrpost['limit'];

		// $keysearch 	= $this->input->get('term');
		$arr 		= array();

		$sqlAuto	 = "select * from product where 1 ";
		$sqlAuto	.= "limit ".$limit_s.",".$limit_e;
		$queryAuto 	= $this->db->query($sqlAuto);
		$checkAuto  = $queryAuto->num_rows();

        if($checkAuto > 0){
			foreach ($queryAuto->result_array() as $key => $value) {

				$value['autokey']	= $limit_s+$key+1;
				$arr[] 				= $value;
			}
		}

		return $arr;
	}
}