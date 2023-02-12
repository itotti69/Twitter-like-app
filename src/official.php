<?php
    session_start();

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //書き込みがあるかどうかの確認
    if (!(isset($_POST['official_id']))) {
        header('Location: management.php');
    }

    //ユーザID 
    $official_id = htmlspecialchars($_POST['official_id'], ENT_QUOTES);

    //DBへの接続
    $dbh = connectDB();

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'SELECT `official_flag` FROM `users_tb` WHERE `user_id` = "' . $official_id . '"';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行
    }

    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $official_flag = $row['official_flag'];
    }


    if ($official_flag == 0) {
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `users_tb` SET `official_flag` = `official_flag` + 1 WHERE `user_id` = "' . $official_id . '"';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
        }
    } else {
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `users_tb` SET `official_flag` = 0 WHERE `user_id` = "' . $official_id . '"';
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
    <title>公認アカウント処理ページ</title>
</head>
<body>
    <!-- 19. 管理者による公認アカウントバッジ付与 -->
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