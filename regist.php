<?php

// 登録ボタンが押されたかチェック
if (isset($_POST['submit']) && $_POST['submit'] == "登録") {

    // フォームデータの取得
    // filter_var フィルタリングに成功した場合は指定されたデータの値を返します。失敗した場合に FALSE を返します。
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING); // 正しい文字列かどうか
    $score = filter_var($_POST['score'], FILTER_VALIDATE_INT); // 正しい数値かどうか
    $email_1 = filter_var($_POST['email_1'], FILTER_VALIDATE_EMAIL); // 正しいアドレスかどうか
    $email_2 = $_POST['email_2'];

    // エラーチェック
    $errors = array();
    if ($name == false) {
        $errors['name'] = "名前を入力してください";
    }
    if ($score == false) {
        $errors['score'] = "スコアを入力してください";
    }
    if ($email_1 == false) {
        $errors['email_1'] = "正しいメールアドレスを入力してください";
    }
    if (strcmp($email_1, $email_2) != 0) {
        $errors['email_not_equal'] = "入力された二つのメールアドレスが違います";
    }

    // エラーチェック
    if (0 < count($errors)) {
        // エラーメッセージを連結してアラート表示
        $alertMsg = "";
        foreach ($errors as $key => $value) {
            $alertMsg = $alertMsg . $value . '\n';
        }
        echo "<script type='text/javascript'>alert('".$alertMsg."');</script>";
    } else {
        // 送信処理

        // 送信データ
        $sendData = array(
            'name' => $name,
            'score' => $score,
            'mail_address' =>  $email_1
        );

        // 引数をURLエンコード  
        $query = http_build_query($sendData);

        // 接続URL
        //$url = 'http://web-network.sakura.ne.jp/games2020/4193321/ShootingGame/db2/add_score.php?' . $query;

        $url = 'http://web-network.sakura.ne.jp/games2020/4193321/Game_1/db2/add_score.php';
          // cURLセッションで通信
          $ch = curl_init();

          // POST
          curl_setopt($ch, CURLOPT_POST, TRUE);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // 送信するデータ
  
          // GET POST共通
          curl_setopt($ch, CURLOPT_USERPWD, "student:jyobi0200021 "); // ベーシック認証
          curl_setopt($ch, CURLOPT_URL, $url); // 取得するURLを指定
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 実行結果を文字列で返す
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // サーバー証明書の検証を行わない
  
          // URLの情報を取得
          $response =  curl_exec($ch);
  
          // ステータスコード取得
          $httpcode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
  
          // セッションを終了!
          curl_close($conn);
  
          if ($httpcode == 200) {
              // ランキングページへ移動
              header("Location: ranking.php");
              exit; // 処理終了
  
          } else {
              echo "登録失敗";
          }
      }
  }
  ?>
  
  <!DOCTYPE html>
  <html lang="ja">
  
  <head>
      <meta charset="utf-8">
      <title>スコア登録画面</title>
  </head>
  
  <body>
      <h1>スコア登録</h1>
      <form action="regist.php" method="POST">
          <p>名前：<br>
              <input type="text" name="name" value="<?php echo $name ?>"></p>
          <p>スコア：<br>
              <input type="number" name="score" value="<?php echo $score ?>"></p>
          <p>当選ご連絡用メールアドレス：<br>
              <input type="email" name="email_1" value="<?php echo $email_1 ?>" size="30" maxlength="50">
              <br>
              <input type="email" name="email_2" value="<?php echo $email_2 ?>" size="30" maxlength="50" oncopy="return false" onpaste="return false" oncontextmenu="return false">
              <p><input type="submit" name="submit" value="登録"></p>
      </form>
  </body>
  
  </html>