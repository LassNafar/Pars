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
//        var_dump($idPosts);
        foreach ($idPosts as $val) {
            
            $time_start = microtime(1);
            
            self::$post = $this->db->queryContent($val['id']);
            
            $analyze = $this->Analyze();
            if ($analyze != false) {
                $this->db->insertName($analyze);
            }
            
            $time_end = microtime(1);
            $time = $time_end - $time_start;
            echo "\nReadPost: ".$time;
        }
    }
    
    public function test()
    {
            self::$post = $this->db->queryContent(36748);
            echo self::$post;
            $analyze = $this->Analyze();
            if ($analyze != false) {
                $this->db->insertName($analyze);
            }
    }
    
    public function Analyze()
    {
        $time_start = microtime(1);
//        echo self::$post;
        
        $r= new SearchName;
        $result = $r->analyzeName();
//        var_dump($result);
        
        $time_end = microtime(1);
        $time = $time_end - $time_start;
        echo "\nAnalyze: ".$time;
        return $result;
    }
}
