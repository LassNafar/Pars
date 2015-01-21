<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>
    function test(Name,key){
        //alert(Name);
        $.ajax({
            type:"GET",
            url: "dbInsert.php",
            data: 'name='+Name,
            dataType: "text",
            success: function (data){
                $("#"+key).remove();
            },
        });
    }
    function del(){
        $.ajax({
            type:"POST",
            url: "delTab.php", 
            dataType: "text",
            success: function (data){
                alert("Таблица очищена");
                window.location.reload();
            },
        });
    }
</script>
</head>
<body>
<?php 
$bd = Db::getInstance();
$Names = $bd->insertAnother();
?>
<table>
<?php foreach ($Names as $key => $val) :?>
<tr id="<?=$key?>" ><td><?=$val['Name']?></td><td><?=$val['Count']?></td><td><a href="" onclick='test("<?=$val['Name']?>","<?=$key?>"); return false;'>Добавить в исключения</a></td></tr>
<?php endforeach;?>
<table>
<a href="" onclick="del(); return false;" >Очистить таблицу</a>
</body>
</html>
