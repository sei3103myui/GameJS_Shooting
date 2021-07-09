<?php
//DB情報読み込み（他のPHPファイル読み込み）
require_once('login.php');
//
try{
    $pdo = new PDO($dsn,$user,$password);

    $sql = 'SELECT * FROM `ranking-4193321` ORDER BY score DESC LIMIT 5;';
    $data = $pdo->query($sql);

    if(!empty($data)){
        foreach($data as $value){
            //
            $result[] = array(
            "id" => $value["id"],
            "name" => $value["name"],
            "score" => $value["score"],
            );
        }
    }
}catch(PDOException $e){
    echo $e->getMessage();
    die();
}

//
echo json_encode($result);

$pdo = null;
?>