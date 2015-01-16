<?php
    $time_start = microtime(1);
    require_once "AutoLoad.php";
    
    $r= new ReadPosts;
    $r->readAllPosts();
//    $r = new DbWord;
//    $r->post();
    
    $time_end = microtime(1);
    $time = $time_end - $time_start;
    
    echo "<br>Време выполнения: ".$time;
    //var_dump($r->analyzeCompany()); var_dump($r->compareName(array("1" => "111", "2" => "111", "3" => "111")));