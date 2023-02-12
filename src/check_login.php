<?php
    //接続関数の呼び出し
    require_once(__DIR__. '/functions.php');

    //セッションの生成
    session_start();

    //送られてこなかったらもう一度入力をやり直しさせる
    if (!(isset($_POST['user']) && isset($_POST['pass']))) {
        header('Location: login.html');
    }

    //POSTでおくられてきたユーザ名/パスワードを変数に置き換える
    $user = htmlspecialchars($_POST['user'], ENT_QUOTES);
    $pass = htmlspecialchars($_POST['pass'], ENT_QUOTES);

    //DBへの接続
    $dbh = connectDB();

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'SELECT `user_id`, `account_name`, `twitter_flag`,  `lock_flag` FROM `users_tb`
        WHERE `user_name` = "' . $user . '"
        AND `login_password` = "' . $pass . '"';

        $sth = $dbh->query($sql);  //SQLの実行

        //データの取得
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
    }

    //認証
    if (count($result) == 1) {  //配列数が唯一の場合
        echo $result[0]['account_name'];  
        //ログイン成功
        $login = 'OK';
        //表示用ユーザ名をセッション変数に保存
        $_SESSION['user_id'] = $result[0]['user_id'];
        $_SESSION['account_name'] = $result[0]['account_name'];

        //twitter_flagで取得した値を$_SESSION['twitter_flag']に代入
        $_SESSION['twitter_flag'] = $result[0]['twitter_flag'];
        $_SESSION['lock_flag'] = $result[0]['lock_flag'];
    } else {
        //ログイン失敗
        $login = 'Error';
    }

    $shh = null;  //データの消去
    $dbh = null;  //DBを閉じる

    $_SESSION['likes'] = 0;

    //セッション変数に代入
    $_SESSION['login'] = $login;

    $_SESSION['user'] = $user;

    //移動
    if ($login == 'OK') {
        //ログイン成功ならtwitter_home.phpにジャンプ
        header('Location: twitter_home.php');
    } else {
        //ログイン失敗：ログインフォーム画面
        header('Location: login.html');
    }
?>
