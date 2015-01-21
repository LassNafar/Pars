<?php
    require_once "AutoLoad.php";
    
    if (Db::getInstance()->check()!=false) {
        $r= new ReadPosts;
        $r->readAllPosts();
    }
    
    require_once "Inspection.php";