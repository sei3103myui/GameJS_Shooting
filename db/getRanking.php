<?php
//DB情報読み込み（他のPHPファイル読み込み）
require_once('login.php');

//DB接続チェック
$conn = new mysqli($hn,$un,$pw,$db);
if($conn->connect_error){
    die("Fatal Error 1");//接続失敗
}

//DB操作
$query="SELECT * FROM `ranking-4193321` ORDER BY score DESC LIMIT 5;";

$stmt = $conn->query($query);//クエリの実行
if(!$stmt){
    die("Fatal Error 2");//クエリエラー
}

//受け取ったデータを1件ずつ配列へ移す
while($row = $stmt->fetch_assoc()){
    $result[] = array(
        "id" => $row["id"],
        "name" => $row["name"],
        "score" => $row["score"]
    );
}

//
echo json_encode($result);

//接続終了
$stmt->close();
$conn->close();
?>