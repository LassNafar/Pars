<?php
    include "Db.php";
    $bd = Db::getInstance();
    $bd->deleteWord($_GET['name']);
