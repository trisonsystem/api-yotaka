<?php
class MProducttype extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function readProducttype($arrpost){

		// debug($arrpost,true);
		$arr = array('status_flag'=>0,'msg'=>'no data');

		$limit_s = ($arrpost['page'] > 1)? ($arrpost['page']*$arrpost['limit'])-$arrpost['limit'] : 0;
		$limit_e = $arrpost['limit'];

		// $keysearch 	= $this->input->get('term');
		$arr 		= array();

		$sqlAuto	 = "select * ";
		$sqlAuto	.= "from m_product_type as pt ";
		$sqlAuto	.= "where 1 order by pt.id desc ";
		$sqlAuto	.= "limit ".$limit_s.",".$limit_e;
		$queryAuto 	= $this->db->query($sqlAuto);
		$checkAuto  = $queryAuto->num_rows();

        if($checkAuto > 0){
        	$arr['status_flag'] = 1;
			foreach ($queryAuto->result_array() as $key => $value) {

				$value['autokey']	= $limit_s+$key+1;
				$arr['data'][] 		= $value;
			}
		}

		// debug($arr);

		return $arr;
	}
}