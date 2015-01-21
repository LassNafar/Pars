<?php

class SearchName
{
    public $arrName;
    public $db;
    
    public function __construct()
    {
        $this->db = Db::getInstance();
        ReadPosts::$post = preg_replace("/(«(.*)»)/U", "", html_entity_decode(ReadPosts::$post));
        $this->db->deleteAnother();
        ReadPosts::$post = preg_replace("/\s/u", " ", ReadPosts::$post);
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
                if (substr_count($val, " ")!=0) {
                    $result[$val] = 1;
                    return $result;
                }
            }
            return false;
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
        $arrCompareName = $this->deleteSmallName($arrName);
        if ($arrCompareName!=false) {
            return $arrCompareName;
        }
        else {
            return false;
        }
    }
    
    public function deleteSmallName($arrName)
    {
        foreach ($arrName as $key => $val) {
            $arrl = substr_count($val, " ");
            if ($arrl==0) {
                $arr0[$val] = 1;
            }
            if ($arrl==1) {
                $arr1[$val] = 1;
            }
            if ($arrl==2) {
                $arr2[$val] = 1;
            }
        }
        
        if (isset($arr0)&&count($arr0)!=0) {
            $this->likeNameOne($arr0);
        }
        if (isset($arr1)&&count($arr1)!=0) {
            $this->likeNameOne($arr1);
        }
        if (isset($arr2)&&count($arr2)!=0) {
            $this->likeNameOne($arr2);
        }
        if (isset($arr1)&&isset($arr0)&&count($arr1)!=0&&count($arr0)!=0) {
            $this->likeNameRang($arr1, $arr0);
        }
        if (isset($arr2)&&isset($arr1)&&count($arr2)!=0&&count($arr1)!=0) {
            $this->likeNameRang($arr2, $arr1);
        }
        
        $arr = array();
        if (isset($arr2)){
            $arr += $arr2;
        }
        if (isset($arr1)){
            $arr += $arr1;
        }
        if (isset($arr0)){
            $arr += $arr0;
        }
        if (count($arr)>0) {
            return $arr;
        }
        else {
            return false;
        }
    }
    
    public function likeNameRang(&$arr1, &$arr0) 
    {
        foreach ($arr1 as $key1 => $val1) {
            foreach ($arr0 as $key0 => $val0) {
                if ($val0!=0&&array_key_exists($key1, $arr1)&&array_key_exists($key0, $arr0)) {
                    $jw = substr_count($key0, " ");
                    $word = explode(" ", $key0);
                    $s = 0;
                    foreach ($word as $val) {
                        if (strlen($val)<=6||substr_count($key1,substr($val, 0, -2))!=0) {
                            $s++;
                        }
                    }
                    if ($s==$jw+1) {
                        $arr1[$key1] += $val0;
                        $arr0[$key0] = 0;
                    }
                }
            }
        }
        $this->clearArr($arr1);
        $this->clearArr($arr0);
    }
    
    public function likeNameOne(&$arr0) 
    {
        $k1 = 0; 
        foreach ($arr0 as $key1 => $val1) {
            $k0 = 0;
            foreach ($arr0 as $key0 => $val0) {
                if ($val1!=0&&$val0!=0&&$k0!=$k1&&array_key_exists($key1, $arr0)&&array_key_exists($key0, $arr0)) {
                    $jw = substr_count($key0, " ");
                    $word = explode(" ", $key0);
                    $s = 0;
                    foreach ($word as $val) {
                        if (strlen($val)<=6||substr_count($key1,substr($val, 0, -2))!=0) {
                            $s++;
                        }
                    }
                    if ($s==$jw+1) {
                        if (strlen($arr0[$key1])<=strlen($arr0[$key0])){
                            $arr0[$key1] += $val0;
                            $arr0[$key0] = 0;
                        }
                        else {
                            $arr0[$key0] += $val1;
                            $arr0[$key1] = 0;
                        }
                    }
                }
                $k0++;
            }
            $k1++;
        }
        $this->clearArr($arr0);
    }
    
    public function clearArr(&$arr) 
    {
        foreach ($arr as $key => $val) {
            if ($val == 0) {
                unset($arr[$key]);
            }
        }
    }
}
