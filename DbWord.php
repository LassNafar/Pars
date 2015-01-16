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
            $time_start = microtime(1);
            
            $this->post = $this->db->queryContent($val['id']);
//            $this->post = $this->db->queryContent(201827);
            
            $time_end = microtime(1);
            $time = $time_end - $time_start;
            echo "\nReadPost: ".$time;
        
            $this->insert();
        }
    }


    public function insert()
    {
        preg_match_all("/\b[а-я]{1,20}\b/u", $this->post, $arrWord);
        foreach ($arrWord[0] as $val) {
            $word = mb_strtolower($val, "utf-8");
            //echo $word;
            $this->db->insertWord($word);
        }
    }
}
