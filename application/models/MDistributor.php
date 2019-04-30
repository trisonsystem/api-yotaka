<?php
class MDistributor extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function readDistributor($arrpost){
		$arr = array('status_flag'=>0,'msg'=>'no data');

		if ( !isset($arrpost["id"]) )        	{ $arrpost["id"]     = "";}
		if ( !isset($arrpost["distributor_name"]) )        	{ $arrpost["distributor_name"]     = "";}

		$limit_s = ($arrpost['page'] > 1)? ($arrpost['page']*$arrpost['limit'])-$arrpost['limit'] : 0;
		$limit_e = $arrpost['limit'];

		$where	= "";
		$where  .= ( $arrpost["id"]      == "" ) ? "" : " AND dis.id='".$arrpost["id"]."'";
		$where  .= ( $arrpost["distributor_name"] 		== "" ) ? "" : " AND dis.name LIKE '%".$arrpost["distributor_name"]."%'";
		$where  .= " and dis.hotel_id='".$arrpost["hotel_id"]."'";

		$arr 		= array();

		$sqlAuto	 = "select * ";
		$sqlAuto	.= "from distributor as dis ";
		$sqlAuto	.= "where 1 and dis.status <> 99 ".$where." order by dis.id desc ";
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

		$arr['status']     = true;
		$arr['optionPage'] = array('page' => $arrpost['page'], 'listCount' => count($arr['data']));

		return $arr;
	}

	public function saveDistributor($arrpost){
		$arr 		 = array('status_flag'=>0,'msg'=>'error');
		$statusQuery = 0;

		if ($arrpost['disid'] =='') {
			$dataIns = array(
                    'name'  => $arrpost['disname'],
                    'address' => $arrpost['disaddress'],
                    'vatid'		=> $arrpost['disvat'],
                    'status'    	=> '0',
                    'hotel_id'		=>$arrpost['hotel_id'],
                    'create_date'  	=> date('Y-m-d H:i:s'),
                    'create_by'		=> $arrpost['user'],
                    'update_date'  	=> date('Y-m-d H:i:s'),
                    'update_by'		=> $arrpost['user']
                );
        	$queryInsert    = $this->db->insert('distributor',$dataIns);
        	$statusQuery 	= $this->db->affected_rows();
		} else {
			$sqlChk	 	= "select * from distributor where name = '".$arrpost['disname']."'";
			$queryChk 	= $this->db->query($sqlChk);
			$checkChk  	= $queryChk->num_rows();

	        if($checkChk == 0){

				$dataUpdate = array(
	                        'name'  => $arrpost['disname'],
		                    'address' => $arrpost['disaddress'],
		                    'vatid'		=> $arrpost['disvat'],
		                    'update_date'  	=> date('Y-m-d H:i:s'),
		                    'update_by'		=> $arrpost['user']
	                    );
				$this->db->where('id', $arrpost['disid']);
	        	$this->db->update('distributor', $dataUpdate);
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

	public function delDistributor($arrpost){
		$arr = array('status_flag'=>0,'msg'=>'error');

       	$dataUpdate = array(
                    'status'       	=> 99,
                    'update_date'  	=> date('Y-m-d H:i:s')
                );
		$this->db->where('id', $arrpost['id']);
    	$this->db->update('distributor', $dataUpdate);

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