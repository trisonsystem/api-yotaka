<?php
class MLogin extends CI_Model {

	public function __construct(){
		parent::__construct();

	}

	public function login( $aData ){
		$arr 		= array('status_flag'=>0,'msg'=>'error');
		$username   = $aData["u_username"];
		$password   = md5($aData["u_password"]);
		$ip 		= $aData["u_ip"];

		$sql 		= "select * from employee where username ='".$username."' AND password = '".$password."'";
		$result 	= $this->db->query($sql);
		$checkAuto  = $result->num_rows();
		$key_random = md5(generateRandomString(10));
        if($checkAuto > 0){
			foreach ($result->result_array() as $key => $value) {
				$aSave = array();
				$aSave["ip"] = $ip;
				$aSave["username"] = $username;
				$this->db->insert("log_login", $aSave);

				$aUpdate = array();
				$aUpdate["key_token"] = $key_random;
				$this->db->where("id", $value["id"]);
				$this->db->update("employee", $aUpdate);

				$arr["status_flag"] = true;
				$arr["level"]   	= $value["rights"];
				$arr["hotel_id"]   	= $value["hotel_id"];
				$arr["key_token"]   = $key_random;
				$arr["msg"] 		= "success"; 
			}
		}else{
			$arr["status_flag"] = false;
			$arr["msg"] = ""; 
		}


		return $arr;
	}

	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function update_login( $aData ){
		$arr 		= array('status_flag'=>0,'msg'=>'error');
		$username   = $aData["u_username"];
		$key_token  = $aData["key_token"];

		$sql 		= "select * from employee where username ='".$username."' AND key_token = '".$key_token."'";
		$result 	= $this->db->query($sql);
		$checkAuto  = $result->num_rows();
        if($checkAuto > 0){
			$arr["status_flag"] = true;
			$arr["msg"] 		= "success"; 
		}else{
			$arr["status_flag"] = false;
			$arr["msg"] = ""; 
		}

		return $arr;
	}

}