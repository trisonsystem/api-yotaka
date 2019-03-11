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
		$sqlAuto	.= "inner join branch as b ON(s.branch_id = b.id)";
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

	public function saveStock($arrpost){

		// debug($arrpost,true);

		$arr 		 = array('status_flag'=>0,'msg'=>'error');
		$statusQuery = 0;

		if($arrpost['productid'] == '' || $arrpost['productbranch'] ==''){
			$arr['msg'] = 'error product id null';
			return $arr;
		}

		if($arrpost['stockid'] ==''){

			$sqlChk	 	= "select * from stock where product_id = '".$arrpost['productid']."' ";
			$queryChk 	= $this->db->query($sqlChk);
			$checkChk  	= $queryChk->num_rows();

	        if($checkChk == 0){
	        	$dataIns = array(
                        'branch_id'     => $arrpost['productbranch'],
                        'product_id'    => $arrpost['productid'],
                        'product_name'  => $arrpost['productname'],
                        'price'    		=> $arrpost['productprice'],
                        'sell'    		=> $arrpost['productsell'],
                        'amount'    	=> $arrpost['productamount'],
                        // 'status'    	=> '1',
                        'remake'    	=> $arrpost['remake'],
                        'create_date'  	=> date('Y-m-d H:i:s'),
                        'update_date'  	=> date('Y-m-d H:i:s'),
                    );
	        	$queryInsert    = $this->db->insert('stock',$dataIns);
	        	$statusQuery 	= $this->db->affected_rows();
			}else{
				$arr['msg'] = 'error product id duplicate';
				return $arr;
			}
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

	public function readEditStock($arrpost){

		$arr = array('status_flag'=>0,'msg'=>'no data');

		$sqlAuto 	 = "select ";
		$sqlAuto 	.= "s.id,s.branch_id,s.product_id,s.amount,s.price,s.sell,s.status as status_stock,s.remake as remake_stock,";
		$sqlAuto 	.= "p.code,p.name as product_name,p.type_id,p.unit_id,p.remake as remake_product,p.status as status_product,";
		$sqlAuto	.= "b.name as branch_name ";
		$sqlAuto	.= "from stock as s ";
		$sqlAuto	.= "inner join product as p ON(s.product_id = p.id) ";
		$sqlAuto	.= "inner join branch as b ON(s.branch_id = b.id) ";
		$sqlAuto	.= "where s.id ='".$arrpost['id']."' ";
		$queryAuto 	= $this->db->query($sqlAuto);
		$checkAuto  = $queryAuto->num_rows();

		if($checkAuto > 0){
        	$arr['status_flag'] = 1;
        	$arr['msg'] 		= $queryAuto->row_array();
        }

		return $arr;
	}

	public function delStock($arrpost){

		$arr = array('status_flag'=>0,'msg'=>'error');

       	$dataUpdate = array(
                    'status'       	=> 0,
                    'update_date'  	=> date('Y-m-d H:i:s')
                );
		$this->db->where('id', $arrpost['id']);
    	$this->db->update('stock', $dataUpdate);

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