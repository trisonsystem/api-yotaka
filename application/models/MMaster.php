<?php
class MMaster extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function autocProduct(){

		// $keysearch 	= $this->input->get('term');
		$arr 		= array();

		$sqlAuto	= "select * from product where 1 limit 50";
		$queryAuto 	= $this->db->query($sqlAuto);
		$checkAuto  = $queryAuto->num_rows();

        if($checkAuto > 0){
			foreach ($queryAuto->result_array() as $key => $value) {

				$arrVal['label'] 	= $value['name'];
				$arrVal['value'] 	= $value;
				$arr[] 				= $arrVal;
			}
		}

		return $arr;
	}
}