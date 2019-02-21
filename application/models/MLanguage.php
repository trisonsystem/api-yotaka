<?php
class MLanguage extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function infoLanguage()
    {
        $this->db->select('*');
        $this->db->from('language');
        $query = $this->db->get();

        $result = $query->result_array();
        return $result;
    }

    public function SaveLanguage($d)
    {
        $this->db->trans_start();
        $this->db->insert('language', $d);
        $this->db->trans_complete();

        return true;
    }

    public function getField(){
        $sql = 'SHOW COLUMNS FROM language';
        $query = $this->db->query($sql);        
        $field = $query->result_array();
        return $field;
    }

    public function SaveFieldLang($d){                
        $this->db->query('ALTER TABLE language ADD '.$d['field'].' TEXT');
        return true;
    }

    public function DeleteLanguage($d){
       $this->db->where('word', $d['word']);
       $this->db->delete('language');
       return true;
    }

    public function DeleteFieldLang($d){
        $this->db->query('ALTER TABLE language Drop '.$d['field']);
        return true;
    }

    public function SaveEditLanguage($d){        
        $to_remove = array("word");
        $arrfilter = array_diff_key($d, array_flip($to_remove));
        
        $this->db->where('word', $d['word']);
        $this->db->update('language', $arrfilter);
        return true;
    }

    public function GetLanguageFromWord($word){
        $this->db->select('*');
        $this->db->from('language');
        $this->db->where('word', $word);
        $query = $this->db->get();

        return $query->result_array();
    }
    public function getLang( $lang = array("lang" => "en")){
        $lang   = $lang["lang"];
        $sql    = " SELECT  word, $lang AS lang FROM language";
        $query  = $this->db->query($sql);
        
        $arr    = array();
        foreach ($query->result_array() as $key => $value) {
            $arr[$value["word"]] = $value["lang"];
        }

        // debug($arr);
        return $arr;
    }
}