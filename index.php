<?php

class Parsing
{
    public $db;

    public function __construct()
    {
        $user = "root";
        $pass = "123456";
        $this->db = new \PDO('mysql:host=localhost;dbname=Search', $user, $pass, array(
              \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ));
    }
    
    public function queryId()
    {
        $result = $this->db->query("SELECT `id` FROM `news`");
        return $result->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function queryContent($id)
    {
        $result = $this->db->query("SELECT `content` FROM `news` WHERE `id` = '".$id."'");
        return $result->fetch(\PDO::FETCH_COLUMN);
    }
    
    public function analyzeName()
    {
    //    preg_match_all("/\s([А-ЯA-Z](.*)),?\s/Uu",
    //                   "Ирина Худайбердыева, генеральный управляющий загородного клуба «Ильдорф»", $result);
        preg_match_all("/(:?\s([А-ЯA-Z]([А-ЯA-Zа-яa-z]*),?\s[А-ЯA-Z]([А-ЯA-Zа-яa-z]*)),?\.?:?\s)|(:?\s([А-ЯA-Z]([А-ЯA-Zа-яa-z]*)),?\.?:?\s)/Uu", $this->queryContent(201827), $result);
    //    echo $result;
        echo $this->queryContent(201827);
        return $result;
    }
    
    public function analyzeCompany()
    {
        preg_match_all("/(«(.*)»)/U",
                       "Ирина «Худайбердыева», «генеральный» управляющий загородного клуба «Ильдорф»", $result);
        return $result;
        }
}

    $r= new Parsing;
    var_dump($r->analyzeName());
    var_dump($r->analyzeCompany());