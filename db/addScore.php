<?php
//DB情報読み込み
require_once('login.php');

//GetParameterのnameを取得
if(isset($_POST['name'])){//nameがあるなら
    $name = $_POST['name'];
}else{
    die("Not Parameter Name");
}

//GetParameterのScoreを取得
if(isset($_POST['score'])){//nameがあるなら
    $score = $_POST['score'];
}else{
    die("Not Parameter Score");
}

//
if(isset($_POST['mail_address'])){
    $score = $_POST['mail_address'];
}else{
    die("Not Parameter Score");
}

//DB接続チェック
$conn = new mysqli($hn,$un,$pw,$db);
if($conn->connect_error){
    die("Fatal Error 1");//接続失敗
}

//DB操作INSERT INTO
$query = "INSERT INTO `ranking-4193321`(`name`,`score`,`mail_address`) VALUES('".$name."','".$score."','".$mail_address."');";
$stmt = $conn->query($query);
if(!$stmt){
    die("Fatal Error 2");
}