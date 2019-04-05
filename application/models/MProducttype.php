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

		if ( !isset($arrpost["producttype_name"]) ) 	    { $arrpost["producttype_name"] 	= "";}

		$where	= "";
		$where  .= ( $arrpost["producttype_name"] 		== "" ) ? "" : " AND pt.name LIKE '%".$arrpost["producttype_name"]."%'";

		// $keysearch 	= $this->input->get('term');
		$arr 		= array();

		$sqlAuto	 = "select * ";
		$sqlAuto	.= "from m_product_type as pt ";
		$sqlAuto	.= "where 1 and status = 0".$where." order by pt.id desc ";
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

	public function saveProducttype($arrpost){

		$arr 		 = array('status_flag'=>0,'msg'=>'error');
		$statusQuery = 0;

		if($arrpost['producttypeid'] ==''){
        	$dataIns = array(
                    'name'  => $arrpost['producttypename'],
                    'status'    	=> '00',
                    'create_date'  	=> date('Y-m-d H:i:s'),
                    'create_by'		=> $arrpost['user'],
                    'update_date'  	=> date('Y-m-d H:i:s'),
                    'update_by'		=> $arrpost['user']
                );
        	$queryInsert    = $this->db->insert('m_product_type',$dataIns);
        	$statusQuery 	= $this->db->affected_rows();

		}else{
			$sqlChk	 	= "select * from m_product_type where name = '".$arrpost['producttypename']."'";
			$queryChk 	= $this->db->query($sqlChk);
			$checkChk  	= $queryChk->num_rows();

	        if($checkChk == 0){

				$dataUpdate = array(
	                        'name'  => $arrpost['producttypename'],
		                    'update_date'  	=> date('Y-m-d H:i:s'),
		                    'update_by'		=> $arrpost['user']
	                    );
				$this->db->where('id', $arrpost['producttypeid']);
	        	$this->db->update('m_product_type', $dataUpdate);
	        	$statusQuery 	= $this->db->affected_rows();
	        }else{
	        	$arr['msg'] = 'error product id duplicate';
				return $arr;
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

	public function readEditProducttype($arrpost){

		$arr = array('status_flag'=>0,'msg'=>'no data');

		$sqlAuto 	 = "select * ";
		$sqlAuto 	.= "from m_product_type as pt ";
		$sqlAuto	.= "where pt.id ='".$arrpost['id']."' ";
		$queryAuto 	= $this->db->query($sqlAuto);
		$checkAuto  = $queryAuto->num_rows();

		if($checkAuto > 0){
        	$arr['status_flag'] = 1;
        	$arr['msg'] 		= $queryAuto->row_array();
        }

		return $arr;
	}

	public function delProducttype($arrpost){

		$arr = array('status_flag'=>0,'msg'=>'error');

       	$dataUpdate = array(
                    'status'       	=> 99,
                    'update_date'  	=> date('Y-m-d H:i:s')
                );
		$this->db->where('id', $arrpost['id']);
    	$this->db->update('m_product_type', $dataUpdate);

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