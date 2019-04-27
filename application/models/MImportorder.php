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
			$headerUpdate = array(
				'order_no' 			=> $h[1]['value'],
				'order_date'		=> date('Y-m-d', strtotime($h[2]['value'])), 
				'order_refer'		=> $h[3]['value'],
				'distributor_id'	=> $h[4]['value'],
				'hotel_id'			=> $arrpost['hotel_id'],
				'remark'			=> $h[7]['value'],
				// 'create_date'		=> date('Y-m-d H:i:s'),
				// 'create_by'			=> $arrpost['user'],
				'update_date'		=> date('Y-m-d H:i:s'),
				'update_by'			=> $arrpost['user']
			);

			$this->db->where('id', $h[0]['value']);
			if($this->db->update('import_order', $headerUpdate)){
				foreach ($l as $k => $v) {
					$listUpdate = array(
						'order_id'			=> $h[0]['value'],
						'product_id'		=> $v['product_id'],
						'amount'			=> $v['amount'],
						'price'				=> $v['price'],
						// 'create_date'		=> date('Y-m-d H:i:s'),
						// 'create_by'			=> $arrpost['user'],
						'update_date'		=> date('Y-m-d H:i:s'),
						'update_by'			=> $arrpost['user']
					);
					$this->db->where('order_id', $h[0]['value']);
					$this->db->where('product_id', $v['product_id']);
					$this->db->update('import_order_list', $listUpdate);
				}
				$statusQuery 	= 1;
			}

			if($statusQuery == 1){
	        	$arr['status_flag'] = 1;
	        	$arr['msg'] 		= 'save succress';
	        }else{
	        	$arr['msg'] 		= 'save error 2';
	        }

	        return $arr;
		}
	}

	public function readImportorder($arrpost){
		$arr = array('status_flag'=>0,'msg'=>'no data');

		if ( !isset($arrpost["doc_id"]) )        			{ $arrpost["doc_id"]     = "";}
		if ( !isset($arrpost["doc_no"]) )        			{ $arrpost["doc_no"]     = "";}
		if ( !isset($arrpost["doc_date"]) )        		{ $arrpost["doc_date"]     = "";}
		if ( !isset($arrpost["refer_no"]) )        			{ $arrpost["refer_no"]     = "";}
		if ( !isset($arrpost["distributor_id"]) )        	{ $arrpost["distributor_id"]     = "";}

		$limit_s = ($arrpost['page'] > 1)? ($arrpost['page']*$arrpost['limit'])-$arrpost['limit'] : 0;
		$limit_e = $arrpost['limit'];

		$where	= "";
		$where  .= ( $arrpost["doc_id"]      == "" ) ? "" : " and io.id='".$arrpost["doc_id"]."'";
		$where  .= ( $arrpost["doc_no"] 		== "" ) ? "" : " and io.order_no like '%".$arrpost["doc_no"]."%'";
		$where  .= ( $arrpost["doc_date"] 		== "" ) ? "" : " and io.order_date like '%".$arrpost["doc_date"]."%'";
		$where  .= ( $arrpost["refer_no"] 		== "" ) ? "" : " and io.order_refer like '%".$arrpost["refer_no"]."%'";
		$where  .= ( $arrpost["distributor_id"] 		== "" ) ? "" : " and io.distributor_id like '%".$arrpost["distributor_id"]."%'";
		$where  .= " and io.hotel_id='".$arrpost["hotel_id"]."'";

		$arr 		= array();

		$sqlAuto	 = "select io.*, di.name as distri_name ";
		$sqlAuto	.= "from import_order as io ";
		$sqlAuto	.= "left join distributor as di on io.distributor_id = di.id ";
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
				// $arr['data'][] = $this->readImportorderList($value['id']);
			}
			$arr['status']     = true;
			$arr['optionPage'] = array('page' => $arrpost['page'], 'listCount' => count($arr['data']));
		}

		// debug($arr);

		return $arr;
	}

	public function readeditImportorder($arrpost){
		$arr = array('status_flag'=>0,'msg'=>'no data');

		$sql  = 'select io.*, di.name as distri_name ';
		$sql .= 'from import_order as io ';
		$sql .= 'left join distributor as di on io.distributor_id = di.id ';
		$sql .= 'where io.id = ' . $arrpost['doc_id'];
		$queryAuto 	= $this->db->query($sql);
		$checkAuto  = $queryAuto->num_rows();

		$arr['status_flag'] = 0;
		if($checkAuto > 0){
			$arr['status_flag'] = 1;
			$arr = $queryAuto->result_array();
			foreach ($arr as $key => $value) {
				$arr['list'] 		= $this->listImportorder( $value['id'] );
			}
		}

		return $arr;
	}

	public function listImportorder( $order_id ){
		$sql = "select impl.*, ";
		$sql .= "pd.id as product_id ,pd.code as product_code, pd.name as product_name, ";
		// $sql .= "pd.type as product_type, pd.u";
		$sql .= "un.name as unit_name ";
		$sql .= "from import_order_list as impl ";
		$sql .= "left join product as pd on impl.product_id = pd.id ";
		$sql .= "left join m_unit as un on pd.unit_id = un.id ";
		$sql .= "where impl.order_id = " . $order_id;
		$query = $this->db->query($sql);
	    $row = $query->result_array();

	    return $row;
	}

	public function readImportorderList($ipo_id){
		$sql = "select * from import_order_list where order_id = " . $ipo_id;

        $query  = $this->db->query($sql);
        $arr = array();
        foreach ($query->result_array() as $key => $value) {
            $arr[] = $value;
        }

        return $arr;
	}

	public function approveImportOrder($arrpost){
		$arr 		 = array('status_flag'=>0,'msg'=>'error');
		$statusQuery = 0;

		if ($arrpost['sapprove'] == "approve") {
			$aSave["status"]   = "1"; 
			$this->db->where("id", $arrpost['doc_id']);
			if ($this->db->update('import_order',$aSave)) {
				$statusQuery 	= $this->db->affected_rows();
			}
		} else {
			$sql = "select * from import_order where id = " . $arrpost['doc_id'];
			$query = $this->db->query($sql);
        	$row = $query->row_array();

        	if($arrpost['remark'] == ""){
        		$re = "null";
        	}else{
        		$re = $arrpost['remark'];
        	}

			$aSave["status"]   = "99";
			$aSave["remark"]	= $row['remark'] . "  /**remark delete " . $re . "**/";

			$this->db->where("id", $arrpost['doc_id']);
			if ($this->db->update('import_order',$aSave)) {
				$statusQuery 	= $this->db->affected_rows();
			}
		}

		if($statusQuery == 1){
        	$arr['status_flag'] = 1;
        	$arr['msg'] 		= 'save succress';
        }else{
        	$arr['msg'] 		= 'save error 2';
        }

        return $arr;

	}
}