<?php
// ランキングTop3のユーザーへ
// 当選おめでとうメールを送る
// メールには順位と名前を記載すること
// 同じ点数の場合は同じ順位とする
// 実行確認はメールやvar_dump();のログで確認して下さい

// DB情報読み込み(他のPHPファイル読み込み)
require_once('login.php');

try {
    // DBへ接続
    $pdo = new PDO($dsn, $user, $password);

    // testテーブルの全データを取得
    $sql = 'SELECT * FROM `ranking-0000000` ORDER BY score DESC LIMIT 3;';
    $data = $pdo->query($sql);

    if (!empty($data)) {
        foreach ($data as $value) {
            // 1レコードずつ配列へ格納
            $result[] = array(
                "id" => $value["id"],
                "name" => $value["name"],
                "score" => $value["score"],
                "mail_address" => $value["mail_address"]
            );
        }
    }
} catch (PDOException $e) {
    echo $e->getMessage(); // エラー出力
    die(); // 接続終了
}

// メール送信
$old_score = 0;
$num = 0;
foreach ($result as $value) {
    // 点数が同じなら同じ順位とする
    if ($old_score != $value["score"]) {
        $old_score = $value["score"];
        $num++;
    }
    // 登録完了メールの送信
    $to = $value["mail_address"];
    $subject = "当選おめでとう";
    $headers = "From: from@example.com";
    $body = $value["name"] . "様\n";
    $body .= "この度はランキングへのご参加ありがとうございます。\n";
    $body .= "ランキングの結果、見事" . $num . "位で当選致しました。\n";
    $body .= "クーポンコードを発行しましたので、ご利用ください。\n";
    $body .= "code: D89FDSDE849\n";
    mail($to, $subject, $body, $headers);
}

// 接続を閉じる
$pdo = null;
