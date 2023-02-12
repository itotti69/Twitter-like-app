<?php
session_start();
//接続用の関数の呼び出し
require_once(__DIR__ . '/functions.php');

//書き込みがあるかどうかの確認
if ((isset($_POST['login_user']))) {
    echo $_POST['login_user'] . 'への';
}

$login_user = htmlspecialchars($_POST['login_user'], ENT_QUOTES);

//DBへの接続
$dbh = connectDB();

$flag = 0;

// if ($dbh) {
//     //データベースへの問い合わせ
//     $sql = 'SELECT * FROM `profile_tb` WHERE `profile_id` = "' . $_SESSION['user_id'] . '"';
//     $sth = $dbh->query($sql); //SQLの実行
// }

if ($dbh) {  //接続に成功した場合
    //データベースへの問い合わせSQL文（文字列）
    $sql = 'INSERT INTO `approval_tb`(`name`, `private_account_name`,`flag`) 
    VALUES ("' . $_SESSION['account_name'] . '","' . $login_user .'", "'. $flag . '")';
    //echo $sql;
    $sth = $dbh->query($sql);  //SQLの実行
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>承認申請管理</title>
</head>
<body>
<?php
    if ($sth == FALSE) {
        echo '■入力に失敗しています。';
    }else{ 
        //ログイン成功ならtwitter_home.phpにジャンプ
        echo '承認申請が完了しました。' . '<br>' .'許可されるまでお待ちください。';
        echo '<a href="twitter_home.php">ホームへ</a>';
    }
    ?>
</body>
</html>