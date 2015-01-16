<?php 

class Db
{
    private static $instance = null;  //экземпляр объекта
    private $db;
    public $letter = array('/а/u' => 0,
                           '/б/u' => 1,
                           '/в/u' => 2,
                           '/г/u' => 3,
                           '/д/u' => 4,
                           '/е/u' => 5,
                           '/ё/u' => 5,
                           '/ж/u' => 6,
                           '/з/u' => 7,
                           '/и/u' => 8,
                           '/й/u' => 9,
                           '/к/u' => 10,
                           '/л/u' => 11,
                           '/м/u' => 12,
                           '/н/u' => 13,
                           '/о/u' => 14,
                           '/п/u' => 15,
                           '/р/u' => 16,
                           '/с/u' => 17,
                           '/т/u' => 18,
                           '/у/u' => 19,
                           '/ф/u' => 20,
                           '/х/u' => 21,
                           '/ц/u' => 22,
                           '/ч/u' => 23,
                           '/ш/u' => 24,
                           '/щ/u' => 25,
                           '/ы/u' => 26,
                           '/э/u' => 27,
                           '/ю/u' => 28,
                           '/я/u' => 29,
                           );

    public static function getInstance() { // получить экземпляр данного класса
        if (self::$instance === null) { // если экземпляр данного класса  не создан
            self::$instance = new self;  // создаем экземпляр данного класса
        }
        return self::$instance; // возвращаем экземпляр данного класса
    }
        
    function __construct()
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
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function queryContent($id)
    {
        $result = $this->db->query("SELECT `content` FROM `news` WHERE `id` = '".$id."' LIMIT 0,1");
        return $result->fetch(\PDO::FETCH_COLUMN);
    }
    
    public function insertName($arrName)
    {
        $time_start = microtime(1);
        foreach ($arrName as $key => $val) {
            if(!$this->db->query("INSERT INTO Name (`Name`, `Count`) VALUES ('".$key."' , '".$val."') ")) {
                $this->db->query("UPDATE Name SET `Count` = `Count` + '".$val."' WHERE `Name` = '".$key."' ");
            }
        }
        
        $time_end = microtime(1);
        $time = $time_end - $time_start;
        echo "\nInsert: ".$time."\n";
    }
    
    public function insertWord($word)
    {
        $firstWord = mb_substr($word, 0, 1, "utf-8");
        if ($firstWord!='ь'&&$firstWord!='ъ') {
            $this->db->query("INSERT INTO Word".$this->letter["/".$firstWord."/u"]." (`Word`) VALUES ('".$word."') ");
        }
    }
    
    public function checkName($word)
    {
//        echo mb_substr($word, 0, 1, "utf-8");
        $word = mb_strtolower($word, "utf-8");
        $arr = array();
        $arrWord = explode(" ", $word);
        foreach ($arrWord as $key => $val) {
            if (!empty($val)) {
                $result = $this->db->query("SELECT `id` FROM Word".$this->letter["/".mb_substr($val, 0, 1, "utf-8")."/u"]." WHERE `Word` = '".$val."'");
                $res = $result->fetchColumn();
                if (empty($res)){
                    $arr[$key] = mb_convert_case($val, MB_CASE_TITLE, 'UTF-8'); 
                    echo $arr[$key];
                    unset($res);
                }
            }
        }

        if (count($arr)>0) {
            return implode(" ", $arr);
        }
        else {
            return false;
        }
    }
}