<?php
class MLanguage extends CI_Model {

	public function __construct(){
		parent::__construct();

    }
    
    public function infoLanguage(){
        $this->db->select('*');
        $this->db->from('language');
        $query = $this->db->get();
        
        $result = $query->result_array();
        return $result;
    }
}