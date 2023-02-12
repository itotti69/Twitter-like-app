<?php
    session_start();

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //書き込みがあるかどうかの確認
    if (!(isset($_POST['retweet_account'])) && !(isset($_POST['retweet_message_id'])) && !(isset($_POST['retweet_comment']))) {
        echo '失敗！！';
    }

    //ユーザ名/パスワード
    $retweet_account = htmlspecialchars($_POST['retweet_account'], ENT_QUOTES);
    $retweet_message_id = htmlspecialchars($_POST['retweet_message_id'], ENT_QUOTES);
    $retweet_comment = htmlspecialchars($_POST['retweet_comment'], ENT_QUOTES);

    //$_SESSION['ret'] = $retweet_message_id;

    //DBへの接続
    $dbh = connectDB();

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'DELETE  FROM `retweet_tb`
         WHERE `user_id` = "' . $_SESSION['user_id'] . '" AND `retweet_id` = "' . $retweet_message_id . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'SELECT `account_name`, `user_name`, `entry_date`, `message` FROM `tweets_tb`
        WHERE `message_id` = "' . $retweet_message_id . '" ';

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
        $sql = 'SELECT `retweet_count` FROM `tweets_tb` WHERE `message_id` = "' . $retweet_message_id . '"';
        $sth = $dbh->query($sql);  //SQLの実行
    }

    while($row = $sth->fetch()) {
        $retweet_count = $row['retweet_count'];
    }

    $count = $retweet_count + 1;
    
    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'UPDATE `tweets_tb` SET `retweet_count` = `retweet_count` + 1 WHERE `message_id` = "' . $retweet_message_id . '"';
        $sth = $dbh->query($sql);  //SQLの実行
    }

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'SELECT `retweet_push` FROM `tweets_tb` WHERE `message_id` = "' . $retweet_message_id . '"';
        $sth = $dbh->query($sql);  //SQLの実行
    }

    while($row = $sth->fetch()) {
        $retweet_push = $row['retweet_push'];
    }

    if ($retweet_push == 0) {
    
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `tweets_tb` SET `retweet_push` =  1 WHERE `message_id` = "' . $retweet_message_id . '"';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
        }
    } else if ($retweet_push == 1) {
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `tweets_tb` SET `retweet_push` =  0 WHERE `message_id` = "' . $retweet_message_id . '"';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
        }

        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `tweets_tb` SET `retweet_count` = `retweet_count` - 2 WHERE `message_id` = "' . $retweet_message_id . '"';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
        }
    } else {
        
    }

    if (isset($retweet_comment)) {
        if ($retweet_push == 0) {
            if ($dbh) {  //接続に成功した場合
                
                //データベースへの問い合わせSQL文（文字列）
                $sql = 'INSERT INTO `retweet_tb`(`retweet_id`, `user_id`, `account_name`,`user_name`, `entry_date`, `message`, `comment`)
                 VALUES ("' . $retweet_message_id . '", "' . $_SESSION['user_id'] . '","' . 
                $account_name . '","' . $user_name . '", "' . $entry_date .'", "' . $message .'", "' . $retweet_comment .'")';
                $sth = $dbh->query($sql);  //SQLの実行
            }
        } 
    }else {
        $retweet_comment = "";
        if ($retweet_push == 0) {
            if ($dbh) {  //接続に成功した場合
                //データベースへの問い合わせSQL文（文字列）
                $sql = 'INSERT INTO `retweet_tb`(`retweet_id`, `user_id`, `account_name`,`user_name`, `entry_date`, `message`, `comment`)
                 VALUES ("' . $retweet_message_id . '", "' . $_SESSION['user_id'] . '","' . 
                $account_name . '","' . $user_name . '", "' . $entry_date .'", "' . $message .'", "' . $retweet_comment .'")';
                $sth = $dbh->query($sql);  //SQLの実行
            }
        } 
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リツイートテーブルに挿入</title>
</head>
<body>
    <!-- 13. リツイート機能 -->
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