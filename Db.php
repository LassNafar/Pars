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
        $result = $this->db->query("SELECT `id` FROM `message` WHERE `id` <= '330732' ORDER BY `id` DESC LIMIT 0,100");
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function queryContent($id)
    {
        $result = $this->db->query("SELECT `mess_content` FROM `message` WHERE `id` = '".$id."' LIMIT 0,1");
        return $result->fetch(\PDO::FETCH_COLUMN);
    }
    
    public function insertName($arrName)
    {
        foreach ($arrName as $key => $val) {
            if(!$this->db->query("INSERT INTO Name (`Name`, `Count`) VALUES ('".$key."' , '".$val."') ")) {
                $this->db->query("UPDATE Name SET `Count` = `Count` + '".$val."' WHERE `Name` = '".$key."' ");
            }
        }
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
        $word = mb_strtolower($word, "utf-8");
        $arr = array();
        $arrWord = explode(" ", $word);
        foreach ($arrWord as $key => $val) {
            if (!empty($val)) {
                $result = $this->db->query("SELECT `id` FROM Word".$this->letter["/".mb_substr($val, 0, 1, "utf-8")."/u"]." WHERE `Word` = '".$val."'");
                $res = $result->fetchColumn();
                if (empty($res)){
                    $arr[$key] = mb_convert_case($val, MB_CASE_TITLE, 'UTF-8');
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
    
    public function deleteAnother() {
        $query = $this->db->query("SELECT `Name` FROM `Another`");
        $badWord = $query->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($badWord as $val) {
            ReadPosts::$post = preg_replace("/".$val["Name"]."/u", "", ReadPosts::$post);
        }
    }
    
    public function insertAnother() 
    {
        $query = $this->db->query("SELECT `Name`,`Count` FROM `Name`");
        $Names = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $Names;
    }
    
    public function check(){
        $query = $this->db->query("SELECT * FROM `Name`");
        $Names = $query->fetchAll(\PDO::FETCH_ASSOC);
        if(!empty($Names)&&count($Names)>0) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function deleteWord($get)
    {
        $query = $this->db->query("INSERT INTO `Another` (`Name`) VALUES ('".trim($get)."')");
        $query = $this->db->query("DELETE FROM `Name` WHERE `Name` = '".trim($get)."'");
    }
    
    public function deleteTable()
    {
        $query = $this->db->query("TRUNCATE TABLE `Name`");
    }
}