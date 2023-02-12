<?php
    session_start();

    //ログインの確認
    if (!((isset($_SESSION['login']) && $_SESSION['login'] == 'OK'))) {
        //ログインフォームへ
        header('Location: login.html');
    }

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //DBへの接続
    // $dbh = connectDB();

    // if ($dbh) {
    //     //データベースへの問い合わせ
    //     $sql = 'SELECT `last_insert_id()` FROM `tweets_tb`';
    //     $sth = $dbh->query($sql); //SQLの実行
    // }
    // $id = $dbh->lastInsertId();
    
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tweet書き込み画面</title>
</head>
<body>
    <!-- 2. アカウントにログインして記事の投稿 -->
    ■メッセージを入力してください。<br>
    <form action="insert_message.php" method="POST" enctype="multipart/form-data" name="form1">
    <a href="twitter_home.php">キャンセル</a>
    <input type="submit" value="ツイートする"><br>
    <textarea name="message" cols="40" rows="5" placeholder="いまどうしてる？"></textarea>
    <!-- 緯度を送信 -->
    <input type="hidden" name="input1" id="latitude">  
    <!-- 経度を送信 -->
    <input type="hidden" name="input2" id="longitude">
    <br>
    画像ファイル: <br>
    <input type="file" name="upload_name" required><br>
    </form><br>

    <button id="locate-now">位置情報取得</button><br/>
    <!-- エラーメッセージ -->
    <p id="message"></p>
    <!-- 緯度表示 -->
    <!-- <p id="latitude" target="_blank"></p><br>  -->
    <!-- 経度表示 --> 
    <!-- <p id="longitude" target="_blank"></p> -->

    ■投票記事作成<br>

    <form action="vote_insert.php" method="POST">
    <textarea name="message" cols="40" rows="5" placeholder="いまどうしてる？"></textarea><br>
    投票記事１:
    <input type="text" name="vote1" placeholder="投票記事１"><br>
    投票記事2:
    <input type="text" name="vote2" placeholder="投票記事2"><br>
    投票記事3:
    <input type="text" name="vote3" placeholder="投票記事3"><br>
    <input type="submit" value="ツイートする"><br>
</form><br>

<!-- js読み込み -->
<script type="text/javascript" src="js/location.js"></script>
    <!-- 位置情報付与について、Safariでの位置情報取得はうまくいかないが、
    Google Chromeでの取得については成功を確認済み -->
    <!-- 位置情報機能参考Webサイト　　「MDN Web Docs」　位置情報API
  URL：　https://developer.mozilla.org/ja/docs/Web/API/Geolocation_API
  閲覧日：2022年2月１日 -->
</body>
</html>