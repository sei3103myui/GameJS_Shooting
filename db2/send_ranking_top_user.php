<?php
//ランキングTOP3のユーザーへ当選おめでとうメールを送る
//メールに順位も記載
//同じ点数の場合は同じ順位とする

require_once('login.php');

try{
    $pdo = new PDO($dsn,$user,$password);

    //
    $sql = 'SELECT * FROM `ranking-4193321` ORDER BY score DESC LIMIT 3;';
    $data = $pdo->query($sql);
    if(empty($data)){
        die("取得失敗");
    }else{
        foreach($data as $value){
            $result[] = array(
                "id" => $value["id"],
                "name" => $value["name"],
                "score" => $value["score"],
                "mail_address" => $value["mail_address"]
            );
        }
    }
    
}catch(PDOException $e){
    //エラー処理
    echo $e->getMessage();
    die();
}

//echo json_enchode($result);

$old_score = 0;
$num = 0;

foreach($result as $value){
    if($old_score != $value["score"]){
        $old_score = $value["score"];
        $num++;
    }
    $to = $value["mail_address"];
    $subject = "ご当選おめでとうございます。";
    $headers = "From: from@example.com";
    $body = "この度はご登録ありがとうございます。\n";
    $body .= "ランキングの集計をいたしましたところ\n";
    $body .= "見事" . $value["name"] . "様が当選いたしましたのでご連絡いたします。\n";
    $body .= "順位は以下に記載しておりますのでご確認ください。\n";
    $body .= "順位は" . $num . "位でした";
    mail($to,$subject,$body,$headers);
}
$pdo = null;