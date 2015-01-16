<?php

class SearchName
{
    public $arrName;
    public $db;
    
    public function __construct()
    {
        $this->db = Db::getInstance();
        ReadPosts::$post = preg_replace("/(«(.*)»)/U", "", html_entity_decode(ReadPosts::$post));
        ReadPosts::$post = preg_replace("/[^А-Яа-я\s]+/u", "#", ReadPosts::$post);
        ReadPosts::$post = preg_replace("/\b[а-я]+\b/u", "#", ReadPosts::$post);
        ReadPosts::$post = preg_replace("/\b[А-Я]{2,}\b/u", "#", ReadPosts::$post);
        ReadPosts::$post = preg_replace("/\bЪ[А-Яа-я]?+\b|\bЬ[А-Яа-я]?+\b/u", "#", ReadPosts::$post);
        ReadPosts::$post = preg_replace("/\s(\s)+/u", " ", ReadPosts::$post);
        ReadPosts::$post = preg_replace("/(\s#\s)+|(\s#)+|#(\s)+/u", "#", ReadPosts::$post);
        ReadPosts::$post = preg_replace("/#(#)+/u", "#", ReadPosts::$post);
        preg_match_all("/(#(.*)?#)/U", ReadPosts::$post, $this->arrName);
    }
    
    public function analyzeName()
    {
        $this->arrName = $this->clearName($this->arrName[2]);
        if (count($this->arrName)>1) {
            $result = $this->compareName($this->arrName);
            return $result;
        }
        elseif (count($this->arrName) == 1) {
            foreach ($this->arrName as $val) {
                $result[$val] = 1;
            }
            return $result;
        }
        else {
            return false;
        }
    }

    public function clearName($arrName)
    {
        foreach ($arrName as $key => $val) {
            if (strlen($val)<9) {
                unset($arrName[$key]);
            }
            else {
                $name = $this->db->checkName($val);
                if ($name!="") {
                    $arrName[$key] = $name;
                }
                else {
                    unset($arrName[$key]);
                }
            }
        }
        return $arrName;
    }
    
    public function compareName($arr)
    {
        foreach ($arr as $val) {
            $arrName[] = trim($val);
        }
        
        $arrCompareName = $this->deleteSmallName($this->deleteSmallName($arrName));
        var_dump($arrCompareName);
        foreach ($arrCompareName as $key => $val) {
            if (substr_count($key, " ") == 0) {
                unset($arrName[$arrCompareName[$key]]);
            }
        }
        return $arrCompareName;
    }
    
    public function deleteSmallName($arrName)
    {
        $arrCompareName = array();
        $l = count($arrName);
        for ($i=0;$i<$l;$i++) {
            if (array_key_exists($i, $arrName)) {
                $sum = 1;
                for ($j=0;$j<$l;$j++) {
                    if (array_key_exists($j, $arrName)&&$i!=$j) {
                        $iw = substr_count($arrName[$i], " ");
                        $jw = substr_count($arrName[$j], " "); 
                        if ($iw>=$jw){
                            if ($jw=0&&substr_count($arrName[$i],substr($arrName[$j], 0, -2))!=0) {
                                $sum++;
                                unset($arrName[$j]);
                            }
                            else {
                                
                            }
                        }
                    }
                }
                $arrCompareName[$arrName[$i]] = $sum;
                unset($arrName[$i]);
            }
        }
        return $arrCompareName;
    }
}
