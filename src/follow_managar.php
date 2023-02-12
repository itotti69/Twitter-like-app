<?php
    //セッション生成
    session_start();

    if(!((isset($_POST['follow_name'])))){
        //チェック
        header('Location: search.php');
    }

    //接続用関数の呼び出し
    require_once(__DIR__.'/functions.php');

    //DBへの接続
    $dbh = connectDB();


    //アカウント名/記事ID
    $follow_name = htmlspecialchars($_POST['follow_name'], ENT_QUOTES);

    $login_user_name = $_SESSION['account_name'];
    $follow_flag = 0;

    //$follow_flag = 0 ＝＞　フォローしていない状態
    //$follow_flag = 1 ＝＞　フォローしている状態

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `login_user_name`, `following_name` FROM `follow_tb`';
        $sth = $dbh->query($sql); //SQLの実行
    }

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `account_name` FROM `users_tb` WHERE `user_name` = "' . $_POST['follow_name'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $account_name = $row['account_name'];
}

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'INSERT INTO `follow_tb`(`login_user_name`,`following_name`, `follow_flag`)
         VALUES ("' . $login_user_name . '","' . $follow_name . '","' . $follow_flag . '")';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行
    }
    //header('Location: twitter_home.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォロー</title>
</head>
<body>
    <!-- フォロー管理ページ -->
    <!-- 4. フォロー機能の追加 -->
    <!-- 理想はボタンを押したユーザのプロフィール画面に飛び、そこで
フォローしたりする -->
<?php echo $_POST['follow_id']?>
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