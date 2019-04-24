<?php
class MImportorder extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function runbill($arrpost){
		$arr = array();
		$sqlAuto	 = "SELECT * FROM import_order AS IO WHERE IO.create_date LIKE '".$arrpost['ddate']."%' AND IO.hotel_id = " . $arrpost['hotel_id'];
		$queryAuto 	= $this->db->query($sqlAuto);
		$checkAuto  = $queryAuto->num_rows();
		$arr['status_flag'] = 0;
        // if($checkAuto < 0){
        	$arr['status_flag'] = 1;
        	$arr['data'] 		= $checkAuto + 1;
		// }

		// debug($arr);

		return $arr;
	}

	public function saveImportOrder($arrpost){

		$arr 		 = array('status_flag'=>0,'msg'=>'error');
		$statusQuery = 0;

		$h = $arrpost['header'];
		$l = $arrpost['list'];

		if($h[0]['value'] == ''){
			
			$header = array(
				'order_no' 			=> $h[1]['value'],
				'order_date'		=> date('Y-m-d', strtotime($h[2]['value'])), 
				'order_refer'		=> $h[3]['value'],
				'distributor_id'	=> $h[4]['value'],
				'hotel_id'			=> $arrpost['hotel_id'],
				'remark'			=> $h[7]['value'],
				'create_date'		=> date('Y-m-d H:i:s'),
				'create_by'			=> $arrpost['user'],
				'update_date'		=> date('Y-m-d H:i:s'),
				'update_by'			=> $arrpost['user']
			);

			if ($this->db->insert('import_order',$header)) {
				$insert_id = $this->db->insert_id();
				$statusQuery 	= $this->db->affected_rows();

				foreach ($l as $k => $v) {
					$list = array(
						'order_id'			=> $insert_id,
						'product_id'		=> $v['product_id'],
						'amount'			=> $v['amount'],
						'price'				=> $v['price'],
						'create_date'		=> date('Y-m-d H:i:s'),
						'create_by'			=> $arrpost['user'],
						'update_date'		=> date('Y-m-d H:i:s'),
						'update_by'			=> $arrpost['user']
					);
					$this->db->insert('import_order_list',$list);
				}
			}

			if($statusQuery == 1){
	        	$arr['status_flag'] = 1;
	        	$arr['msg'] 		= 'save succress';
	        }else{
	        	$arr['msg'] 		= 'save error 2';
	        }

	        return $arr;
			
		}else{

		}
	}

	public function readImportorder($arrpost){

		// debug($arrpost,true);
		$arr = array('status_flag'=>0,'msg'=>'no data');

		$limit_s = ($arrpost['page'] > 1)? ($arrpost['page']*$arrpost['limit'])-$arrpost['limit'] : 0;
		$limit_e = $arrpost['limit'];

		// if ( !isset($arrpost["unit_name"]) ) 	    { $arrpost["unit_name"] 	= "";}

		$where	= "";
		// $where  .= ( $arrpost["unit_name"] 		== "" ) ? "" : " AND un.name LIKE '%".$arrpost["unit_name"]."%'";
		$where  .= " AND io.hotel_id='".$arrpost["hotel_id"]."'";


		// $keysearch 	= $this->input->get('term');
		$arr 		= array();

		$sqlAuto	 = "select * ";
		$sqlAuto	.= "from import_order as io ";
		$sqlAuto	.= "where 1 ".$where." order by io.id desc ";
		$sqlAuto	.= "limit ".$limit_s.",".$limit_e;
		$queryAuto 	= $this->db->query($sqlAuto);
		$checkAuto  = $queryAuto->num_rows();

		$arr['status_flag'] = 0;
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