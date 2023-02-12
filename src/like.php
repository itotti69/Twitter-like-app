<?php
    session_start();

    //ログインの確認
    if (!((isset($_SESSION['login']) && $_SESSION['login'] == 'OK'))) {
        //ログインフォームへ
        header('Location: login.html');
    }

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //書き込みがあるかどうかの確認
    if (!(isset($_POST['like_account'])) && !(isset($_POST['like_message_id']))) {
        header('Location: twitter_home.php');
    }

    //アカウント名/記事ID
    $like_account = htmlspecialchars($_POST['like_account'], ENT_QUOTES);
    $like_message_id = htmlspecialchars($_POST['like_message_id'], ENT_QUOTES);

    $_SESSION['likes'] = $like_message_id;

    //DBへの接続
    $dbh = connectDB();

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'DELETE  FROM `like_tb`
         WHERE `like_id` = "' . $like_message_id . '" AND `account_name` = "' . $like_account . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        //admin_Flagの値もデータベースから取得してくる
        $sql = 'SELECT `account_name`, `user_name`, `entry_date`, `message` FROM `tweets_tb`
        WHERE `message_id` = "' . $like_message_id . '" ';

        $sth = $dbh->query($sql);  //SQLの実行
    }

    while($row = $sth->fetch()) {
        $account_name = $row['account_name'];
        $user_name = $row['user_name'];
        $entry_date = $row['entry_date'];
        $message = $row['message'];
    }


    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        //admin_Flagの値もデータベースから取得してくる
        $sql = 'SELECT `like_counts` FROM `tweets_tb` WHERE `message_id` = "' . $like_message_id . '"';
        $sth = $dbh->query($sql);  //SQLの実行
    }

    while($row = $sth->fetch()) {
        $like_counts = $row['like_counts'];
    }

    $count = $like_counts + 1;

    // echo $like_count;
    
    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'UPDATE `tweets_tb` SET `like_counts` = `like_counts` + 1 WHERE `message_id` = "' . $like_message_id . '"';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行
    }

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        //admin_Flagの値もデータベースから取得してくる
        $sql = 'SELECT `like_push` FROM `tweets_tb` WHERE `message_id` = "' . $like_message_id . '"';
        $sth = $dbh->query($sql);  //SQLの実行
    }

    while($row = $sth->fetch()) {
        $like_push = $row['like_push'];
    }

    if ($like_push == 0) {
    
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `tweets_tb` SET `like_push` =  1 WHERE `message_id` = "' . $like_message_id . '"';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
        }
    } else if ($like_push == 1) {
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `tweets_tb` SET `like_push` =  0 WHERE `message_id` = "' . $like_message_id . '"';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
        }

        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `tweets_tb` SET `like_counts` = `like_counts` - 2 WHERE `message_id` = "' . $like_message_id . '"';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
        }
    } else {
        
    }

    
    if ($like_push == 0) {
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'INSERT INTO `like_tb`(`like_id`, `account_name`,`user_name`, `entry_date`, `message`, `like_push_user`)
             VALUES ("' . $like_message_id . '","' . 
            $account_name . '","' . $user_name . '", "' . $entry_date .'", "' . $message . '", "' . $like_account . '")';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
        }
    } 

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ライクテーブルに挿入</title>
</head>
<body>
<?php
    if ($sth == FALSE) {
        echo '■入力に失敗しています。';
    }else{ 
        //ログイン成功ならtwitter_home.phpにジャンプ
        header('Location: twitter_home.php');
    }
    ?>
</body>
</html>