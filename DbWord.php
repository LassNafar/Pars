<?php

class DbWord
{
    public $db;
    public $post;
    
    public function __construct() {
        $this->db = Db::getInstance();
    }
    
    public function post()
    {
        $idPosts = $this->db->queryId();
        foreach ($idPosts as $val) {
            
            $this->post = $this->db->queryContent($val['id']);
        
            $this->insert();
        }
    }


    public function insert()
    {
        preg_match_all("/\b[а-я]{1,20}\b/u", $this->post, $arrWord);
        foreach ($arrWord[0] as $val) {
            $word = mb_strtolower($val, "utf-8");
            $this->db->insertWord($word);
        }
    }
}
