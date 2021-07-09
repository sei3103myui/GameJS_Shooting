<?php
$btnType = $_POST["get_ranking"];

// $name = $_POST["name"];
// $score = $_POST["score"];

//入力データがあるなら送信する
if(isset($btnType)){
    

    //引数をURLエンコード
    //$query = http_build_query($sendData);

    //接続URL
    $url = 'http://web-network.sakura.ne.jp/games2020/4193321/Game_1/db2/get_ranking.php';

    //cURLセッションで通信
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_USERPWD,"student:jyobi0200021 ");//ベーシック認証
    curl_setopt($ch,CURLOPT_URL,$url);//取得するURLを指定
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);//実行結果を文字列で返す
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);//サーバーの証明書の検証を行わない

    //URLの情報を取得
    $response = curl_exec($ch);

    //ステータスコード取得
    $httpcode = curl_getinfo($ch,CURLINFO_RESPONSE_CODE);
    //セッションを終了
    curl_close($conn);

    // if($httpcode == 200){
    //     //echo "取得完了";
    //     //
    //     $json = mb_convert_encoding($response,'UTF8','ASCII,JIS,UTF-8,ETC-JP,SJIS-WIN');

    //     //echo $json;
    //     //連想配列へのアクセス方法
    //     $arr = json_decode($json,true);

    //     echo '<br>';
    //     // foreach($arr as $data){
    //     //     echo '<p>';
    //     //     echo $data['id'].$data['name'].$data['score'];
    //     //     echo '</p>';
    //     // }
    //     //var_dump($arr[0]);
    // }else{
    //     echo "取得失敗";
    // }
    if ($httpcode == 200) {
        // 受け取った値を第3引数の順にUTF8にエンコード
        $json = mb_convert_encoding(
            $response,
            'UTF8',
            'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'
        );
        // 連想配列へのアクセス方法
        $arr = json_decode($json, true);
    } else {
        echo "取得失敗";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset='utf-8'>
    <title>ランキング画面</title>
</head>
<body>
    <h1>ランキング</h1>
    <form action="ranking.php" method="POST">
    <?php
        if(isset($arr)){
            foreach($arr as $data){
                echo '<p>';//Pタグ開始
                echo $data['id'].$data['name'].$data['score'];
                echo '</p>';//Pタグ終了
            }
        }
    ?>
        <button type="submit" name="get_ranking">"ランキングの取得"</button>
    </form>
</body>
</html>

