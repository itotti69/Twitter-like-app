<?php
    session_start();

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //書き込みがあるかどうかの確認
    if (!(isset($_POST['message']) && isset($_POST['vote1']) && isset($_POST['vote2']) && isset($_POST['vote3']))) {
        header('Location: write_message.php');
    }

    //書き込まれた文章を変数に代入
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES);
    $vote1 = htmlspecialchars($_POST['vote1'], ENT_QUOTES);
    $vote2 = htmlspecialchars($_POST['vote2'], ENT_QUOTES);
    $vote3 = htmlspecialchars($_POST['vote3'], ENT_QUOTES);

    //DBへの接続
    $dbh = connectDB(); 

    $count1 = 0;
    $count2 = 0;
    $count3 = 0;

    //文字数の上限１４０文字に設定
    $limit = 140;

    if (mb_strlen($message) > $limit) {
        $str_check = 1;
    }else{
        if ($dbh) {  //接続に成功した場合
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'INSERT INTO `vote_tb`(`account_name`, `user_name`,`message`,`message1`, `count1`, `message2`, `count2`, `message3`,`count3`) VALUES ("' . $_SESSION['account_name'] . '","' . $_SESSION['user'] . '","' . 
            $message . '","' . $vote1 . '","' . $count1 . '", "' . $vote2 . '", "' . $count2 . '", "' . $vote3 . '","' . $count3 . '")';
            //echo $sql;
            $sth = $dbh->query($sql);  //SQLの実行
            header('Location: twitter_home.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tweet書き込み処理</title>
</head>
<body>
    <?php if($str_check == 1): ?>
    <p>文字数が１４０字を超えています。もう一度書き込みを行ってください。</p>
    <a href="write_message.php">メッセージ書き込みへ戻る</a>
    <?php endif; ?>

    <!-- 文字数制限で参考にした記事 -->
    <!-- phpで文字数を制限し、末尾に『』を追加するコード URL: https://spreadsheep.net/　　-->
    <!-- 閲覧日2022年1月２５日（月） -->
</body>
</html>