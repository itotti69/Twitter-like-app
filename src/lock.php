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
    if (!(isset($_POST['lock_id']))) {
        header('Location: twitter_home.php');
    }

    //ユーザ名/パスワード
    $lock_id = htmlspecialchars($_POST['lock_id'], ENT_QUOTES);

    //DBへの接続
    $dbh = connectDB();

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'SELECT `lock_flag` FROM `users_tb` WHERE `user_id` = "' . $lock_id . '"';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行
    }

    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $lock_flag = $row['lock_flag'];
    }

//フラグを用いて対象アカウントにロックをかける
    if ($lock_flag == 0) {
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `users_tb` SET `lock_flag` = `lock_flag` + 1 WHERE `user_id` = "' . $lock_id . '"';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
        }
    } else {
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `users_tb` SET `lock_flag` = 0 WHERE `user_id` = "' . $lock_id . '"';
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
    <title>アカウント凍結処理ページ</title>
</head>
<body>
    <!-- 20. 管理者によるアカウントの凍結 -->
    <?php
    if ($sth == FALSE) {
        echo '■入力に失敗しています。';
    }else{ 
        //ログイン成功ならtwitter_home.phpにジャンプ
        header('Location: management.php');
    }
    ?>
</body>
</html>