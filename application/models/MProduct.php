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

	public function addProduct($arrpost){

		$arr = array('status_flag'=>0,'msg'=>'error');

		$dataIns = array(
                        'code'       => $arrpost['barcode'],
                        'name'       => $arrpost['productname'],
                        'type_id'    => '1',
                        'unit_id'    => '1',
                        'create_date'  => date('Y-m-d H:i:s'),
                        'update_date'  => date('Y-m-d H:i:s'),
                    );
        $queryInsert    = $this->db->insert('product',$dataIns);
        $statusQuery 	= $this->db->affected_rows();
        $insId 			= $this->db->insert_id();

        if($statusQuery == 1){
        	$arr['status_flag'] = 1;
        	$arr['msg'] 		= 'save succress';
        }else{
        	$arr['msg'] 		= 'error insert';
        }

        return $arr;

	}

}