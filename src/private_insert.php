<?php
    session_start();

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //書き込みがあるかどうかの確認
    if (!(isset($_POST['private_id']))) {
        header('Location: twitter_home.php');
    }

    $private_id = htmlspecialchars($_POST['private_id'], ENT_QUOTES);

    //DBへの接続
    $dbh = connectDB();

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'SELECT `private_flag` FROM `users_tb` WHERE `user_id` = "' . $private_id . '"';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行
    }

    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $private_flag = $row['private_flag'];
    }

    //フラグを用いて、非公開か公開アカウントかを設定
    if ($private_flag == 0) {
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `users_tb` SET `private_flag` =  1 WHERE `user_id` = "' . $private_id . '"';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
        }
    } else {
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'UPDATE `users_tb` SET `private_flag` = 0 WHERE `user_id` = "' . $private_id . '"';
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
    <title>非公開アカウント処理ページ</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
</head>
<body>
<a href="private.php" class="linkbutton"><i class="fas fa-arrow-alt-circle-left"></i></a><br>
<?php
    if ($sth == FALSE) {
        echo '■入力に失敗しています。';
    }else{ 
        if($private_flag == 0){
            echo 'アカウントを非公開に設定しました。';
        }else{
            echo 'アカウントを公開に設定しました。';
        }
    }
    ?>
</body>
</html>