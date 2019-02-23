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
		$sqlAuto	.= "order by id desc ";
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

	public function saveProduct($arrpost){

		// debug($arrpost,true);

		$arr = array('status_flag'=>0,'msg'=>'error');

		if($arrpost['id'] ==''){
			$dataIns = array(
                        'code'       => $arrpost['barcode'],
                        'name'       => $arrpost['productname'],
                        'type_id'    => '1',
                        'unit_id'    => '1',
                        'create_date'  => date('Y-m-d H:i:s'),
                        'update_date'  => date('Y-m-d H:i:s'),
                    );
	        $queryInsert    = $this->db->insert('product',$dataIns);
	        // $insId 			= $this->db->insert_id();
		}else{

			$dataUpdate = array(
                        'code'       	=> $arrpost['barcode'],
                        'name'       	=> $arrpost['productname'],
                        'type_id'    	=> '1',
                        'unit_id'    	=> '1',
                        'update_date'  	=> date('Y-m-d H:i:s')
                    );
			$this->db->where('id', $arrpost['id']);
        	$this->db->update('product', $dataUpdate);
		}

		$statusQuery 	= $this->db->affected_rows();

        if($statusQuery == 1){
        	$arr['status_flag'] = 1;
        	$arr['msg'] 		= 'save succress';
        }else{
        	$arr['msg'] 		= 'save error';
        }

        return $arr;

	}

	public function readEditProduct($arrpost){

		$arr = array('status_flag'=>0,'msg'=>'no data');

		$sqlAuto	= "select * from product where id ='".$arrpost['id']."' ";
		$queryAuto 	= $this->db->query($sqlAuto);
		$checkAuto  = $queryAuto->num_rows();

		if($checkAuto > 0){
        	$arr['status_flag'] = 1;
        	$arr['msg'] 		= $queryAuto->row_array();
        }

		return $arr;
	}

	public function delProduct($arrpost){

		$arr = array('status_flag'=>0,'msg'=>'error');

       	$dataUpdate = array(
                    'status'       	=> 0,
                    'update_date'  	=> date('Y-m-d H:i:s')
                );
		$this->db->where('id', $arrpost['pid']);
    	$this->db->update('product', $dataUpdate);

        $statusQuery 	= $this->db->affected_rows();

        if($statusQuery == 1){
        	$arr['status_flag'] = 1;
        	$arr['msg'] 		= 'Delect succress';
        }else{
        	$arr['msg'] 		= 'Delect error';
        }

        return $arr;
	}

}