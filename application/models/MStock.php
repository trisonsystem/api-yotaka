<?php
class MStock extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function readStock($arrpost){

		// debug($arrpost,true);
		$arr = array('status_flag'=>0,'msg'=>'no data');

		$limit_s = ($arrpost['page'] > 1)? ($arrpost['page']*$arrpost['limit'])-$arrpost['limit'] : 0;
		$limit_e = $arrpost['limit'];

		// $keysearch 	= $this->input->get('term');
		$arr 		= array();

		$sqlAuto	 = "select s.id,s.amount,s.price,s.sell,s.status,p.code,p.name,p.type_id,p.unit_id,b.name as branch_name ";
		$sqlAuto	.= "from stock as s ";
		$sqlAuto	.= "inner join product as p ON(s.product_id = p.id)";
		$sqlAuto	.= "inner join branch as b ON(s.product_id = b.id)";
		$sqlAuto	.= "where 1 order by s.id desc ";
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