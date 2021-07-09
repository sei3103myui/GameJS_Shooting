<?php
// DB情報読み込み(他のPHPファイル読み込み)
require_once('login.php');

// // GetParameterのnameを取得
// if (isset($_GET['name'])) { // nameがあるなら
//     $name = $_GET['name'];
// } else {
//     die("Not Parameter Name");
// }

// // GetParameterのScoreを取得
// if (isset($_GET['score'])) {
//     $score = $_GET['score'];
// } else {
//     die("Not Parameter Score");
// }

// GetParameterのnameを取得
if (isset($_POST['name'])) { // nameがあるなら
    $name = $_POST['name'];
} else {
    die("Not Parameter Name");
}

// GetParameterのScoreを取得
if (isset($_POST['score'])) {
    $score = $_POST['score'];
} else {
    die("Not Parameter Score");
}

// GetParameterのScoreを取得
if (isset($_POST['mail_address'])) {
    $mail_address = $_POST['mail_address'];
} else {
    die("Not Parameter mail_address");
}

try{
    //DBへ接続
    $pdo = new PDO($dsn, $user, $password);  

    //データの追加
    $stmt = $pdo ->prepare("INSERT INTO `ranking-4193321`(name, score, mail_address) VALUES (:name, :score, :mail_address)");
    $stmt->bindValue(':name',$name,PDO::PARAM_STR);
    $stmt->bindValue(':score',$score,PDO::PARAM_INT);
    $stmt->bindValue(':mail_address', $mail_address, PDO::PARAM_STR);
    $stmt->execute();
}catch(PDOException $e){
    //エラー処理
    echo $e->getMessage();
    die();
}

$pdo = null;

$to = $mail_address;
$subject = "ランキング登録完了";
$headers = "From: from@example.com";
$body = "ランキングへのご登録が完了しました。\n";
$body .= "上位1000人に見事ご登録された際には改めてご連絡させて頂きます。\n";
$body .= "ランキング結果は以下のURLからご確認できます。\n";
$body .= "https://web-network.sakura.ne.jp/games2020/4193321/Game_1/ranking.php";

mail($to,$subject,$body,$headers);