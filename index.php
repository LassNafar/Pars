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
    //    preg_match_all("/([^«]:?\s([А-ЯA-Z]([А-ЯA-Zа-яa-z]*)\s[А-ЯA-Z]([А-ЯA-Zа-яa-z]*)),?\.?:?\s[^»])|([^«]:?\s([А-ЯA-Z]([А-ЯA-Zа-яa-z]*)),?\.?:?\s[^»])/Uu", $this->queryContent(201827), $result);
        $post = preg_replace("/(«(.*)»)/U", "", html_entity_decode($this->queryContent(201827)));
        preg_match_all("/[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+|[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+|[А-ЯA-Z]+[а-я]+|[А-Я]{1}\.[\s]+[А-ЯA-Z]{1}\.[\s]+[А-Я]+[а-я]+|[А-ЯA-Z]{1}\.[\s]+[А-Я]+[а-я]+/u", $post, $result);
        echo $post;
//    echo $result;
        return $result;
    }
    /*
    public function clearName($arrName)
    {
        foreach ($arrName as $key => $val) {
            
        }
    }*/
    
    public function compareName($arrName)
    {
        $l = count($arrName);
        for ($i=0;$i<$l;$i++) {
            if (isset($arrName[$i])) {
                for ($j=$i+1;$j<=$l;$j++) {
                    $var = similar_text($arrName[$i], $arrName[$j],$compare);
                    if ($compare>60) {
                        if (!array_key_exists($arrName[$i], $arr)) {
                            $arr[$arrName[$i]] = 2;
                            unset($arrName[$j]);
                        }
                        else {
                            $arr[$arrName[$i]] += 1;
                            unset($arrName[$j]);
                        }
                    }
                }
            }
        }
        return $arr;
    }
    
    public function analyzeCompany()
    {
        preg_match_all("/(«(.*)»)/U",
                       "Ирина «Худайбердыева», «генеральный» управляющий загородного клуба «Ильдорф»", $result);
        return $result;
        }
}

    $r= new Parsing; var_dump($r->analyzeName()); 
    //var_dump($r->analyzeCompany()); var_dump($r->compareName(array("1" => "111", "2" => "111", "3" => "111")));