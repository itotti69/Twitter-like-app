<?php
    session_start();

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //書き込みがあるかどうかの確認
    if (!(isset($_POST['retweet_account'])) && !(isset($_POST['retweet_message_id']))) {
       echo '失敗！！';
    }else{
        
    }
//DBへの接続
$dbh = connectDB();
    //ユーザ名/パスワード
    $retweet_account = $_POST['retweet_account'];
    $retweet_message_id = htmlspecialchars($_POST['retweet_message_id'], ENT_QUOTES);

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT * FROM `retweet_tb` WHERE `retweet_id` = "'. $retweet_message_id . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>引用リツイートページ</title>
</head>
<body>
    <!-- 15. コメントをつけてリツイート -->
<a href="twitter_home.php">キャンセル</a>
<form action="retweet.php" method="POST">
    <input type="hidden" name="retweet_account" value="<?php echo $retweet_account; ?>">
    <input type="hidden" name="retweet_message_id" value="<?php echo $retweet_message_id; ?>">
    <textarea name="retweet_comment" cols="30" rows="10" placeholder="コメントを追加する"></textarea>
    <input type="submit" value="ツイートする">
</form>
<?php
 while ($row = $sth->fetch()) {  //瞬時呼び出し
        echo '<table><tr>';
        echo '<td>'.$row['account_name'].'</td>';
        echo '<td>'.$row['user_name'].'</td>';
        echo '<td>'.$row['entry_date'].'</td></tr>';
        echo '<tr><td>'.$row['message'].'</td></tr>';
        echo '</table>';
    }
?>
</body>
</html>