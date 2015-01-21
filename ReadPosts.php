<?php

class ReadPosts
{
    public $db;
    public static $post;
    
    public function __construct() {
        $this->db = Db::getInstance();
    }
    
    public function readAllPosts()
    {
        $idPosts = $this->db->queryId();
        foreach ($idPosts as $val) {
            self::$post = $this->db->queryContent($val['id']);
            $analyze = $this->Analyze();
            if ($analyze != false) {
                $this->db->insertName($analyze);
            }
        }
    }
    
    public function test()
    {
            self::$post = $this->db->queryContent(36748);
            $analyze = $this->Analyze();
            if ($analyze != false) {
                $this->db->insertName($analyze);
            }
    }
    
    public function Analyze()
    {
        $r= new SearchName;
        $result = $r->analyzeName();
        
        return $result;
    }
}
