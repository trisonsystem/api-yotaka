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
		$sqlAuto	.= "where 1 ".$where." order by pt.id desc ";
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
                    'name'  => $arrpost['productname'],
                    'status'    		=> $arrpost['productprice'],
                    'remake'    	=> $arrpost['remake'],
                    'create_date'  	=> date('Y-m-d H:i:s'),
                    'update_date'  	=> date('Y-m-d H:i:s'),
                );
        	$queryInsert    = $this->db->insert('stock',$dataIns);
        	$statusQuery 	= $this->db->affected_rows();

		}else{

			$sqlChk	 	= "select * from stock where product_id = '".$arrpost['productid']."' and id !='".$arrpost['stockid']."' ";
			$queryChk 	= $this->db->query($sqlChk);
			$checkChk  	= $queryChk->num_rows();

	        if($checkChk == 0){

				$dataUpdate = array(
	                        'branch_id'     => $arrpost['productbranch'],
	                        'product_id'    => $arrpost['productid'],
	                        'product_name'  => $arrpost['productname'],
	                        'amount'    	=> $arrpost['productprice'],
	                        'price'    		=> $arrpost['productsell'],
	                        'sell'    		=> $arrpost['productamount'],
	                        'remake'    	=> $arrpost['remake'],
	                        'update_date'  	=> date('Y-m-d H:i:s')
	                    );
				$this->db->where('id', $arrpost['stockid']);
	        	$this->db->update('stock', $dataUpdate);
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
}