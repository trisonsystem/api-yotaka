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


    // *********************************************************************************************

    public function search_language( $aData ){
        $lm = 15;
        if ( !isset($aData["page"]) )               { $aData["page"]                = 1;}
        if ( !isset($aData["language_word"]) )      { $aData["language_word"]   = "";}
        if ( !isset($aData["language_en"]) )      { $aData["language_en"]   = "";}
        if ( !isset($aData["language_th"]) )    { $aData["language_th"]     = "";}

        $LIMIT   = ( $aData["page"]     == "" ) ? "0, $lm" : (($aData["page"] * $lm) - $lm).",$lm" ;

        $WHERE   = "";
        $WHERE  .= ( $aData["language_word"]        == "" ) ? "" : " AND LG.word LIKE '%".$aData["language_word"]."%'";
        $WHERE  .= ( $aData["language_en"]        == "" ) ? "" : " AND LG.en LIKE '%".$aData["language_en"]."%'";
        $WHERE  .= ( $aData["language_th"]        == "" ) ? "" : " AND LG.th LIKE '%".$aData["language_th"]."%'";

        $sql = "SELECT *
                FROM language AS LG
                WHERE 1 = 1 $WHERE
                ORDER BY LG.word DESC LIMIT $LIMIT";

        $query  = $this->db->query($sql);
        $arr = array();
        foreach ($query->result_array() as $key => $value) {
            $arr[] = $value;
        }
        $arr["limit"] = $lm;
        // debug($arr);
        return $arr;
    }

    public function save_data( $aData ){
        $aReturn = array();
        $arrParam = array('txtLanguage_word', 'etxtLanguageEN', 'etxtLanguageTH');
        foreach ($arrParam as $key) {
            if(!isset($aData[$key])){
                return array( "flag"=>false, "msg"=>"Parameter Error ".$key);
                exit();
            }
        }

        $aSave   = array();
        $aSave["en"]  = $aData["etxtLanguageEN"];
        $aSave["th"]  = $aData["etxtLanguageTH"];
        if ($aData['txtLanguage_word'] == "0") {
            $aSave["word"]  = $aData["etxtLanguageWord"];
            if ($this->db->replace('language', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        } else {
            $this->db->where("id", $aData["txtLanguage_word"] );
            if ($this->db->update('language', $aSave)) {
                $aReturn["flag"] = true;
                $aReturn["msg"] = "success";
            }else{
                $aReturn["flag"] = false;
                $aReturn["msg"] = "Error SQL !!!";
            }
        }

        return $aReturn;

    }

    public function chang_status( $aData ){
        // $aSave["update_date"]           = date("Y-m-d H:i:s");
        // $aSave["update_by"]             = $aData["user"];
        // $aSave["status"]    = $aData["status"];
        // debug($aData, true);
        $this->db->where("word", $aData["language_word"]);
        if ($this->db->delete('language')) {
            $aReturn["flag"] = true;
            $aReturn["msg"] = "success";
        }else{
            $aReturn["flag"] = false;
            $aReturn["msg"] = "Error SQL !!!";
        }

        return $aReturn;
    }
}