    <?php
    session_start();

    // //ログインの確認
    if (!((isset($_POST['account_name']) && isset($_POST['user_name']) && isset($_POST['login_password']) &&isset($_POST['twitter_flag'])))) {
        //ログインフォームへ
        header('Location: login.html');
    }

    // //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //create_new.phpからPOSTで送られてきたデータを一旦変数に置き換える
    $account_name = htmlspecialchars($_POST['account_name'], ENT_QUOTES);
    $user_name = htmlspecialchars($_POST['user_name'], ENT_QUOTES);
    $login_password = htmlspecialchars($_POST['login_password'], ENT_QUOTES);
    $twitter_flag = htmlspecialchars($_POST['twitter_flag'], ENT_QUOTES);
    $official_flag = 0;
    $lock_flag = 0;
    $private_flag = 0;

    //DBへの接続
    $dbh = connectDB();

    if ($dbh) {
        //INSERT INTOを使ってデータベースにアカウント情報を代入する
       //データベースへの問い合わせSQL文（文字列）
        $sql = 'INSERT INTO `users_tb`(`account_name`, `user_name`, `login_password`, `twitter_flag`, `private_flag`, `official_flag`, `lock_flag`)
        VALUES("' . $account_name. '","' . $user_name . '","' . $login_password .'","' .$twitter_flag .'","' .$private_flag .'","' .$official_flag .'","' .$lock_flag .'")';
        $sth = $dbh->query($sql);  //SQLの実行
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitterアカウント作成</title>
</head>
<body>
    <!-- 課題１.アカウントの作成 -->
    <!-- 作成が完了したことを知らせる-->
    <?php echo $account_name . "の作成が完了しました"; ?>
    <li>
        <a href="login.html">ログイン画面へ進む→</a>
    </li>
</body>
</html>
