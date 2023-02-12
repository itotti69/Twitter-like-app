<?php
    session_start();

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //書き込みがあるかどうかの確認
    if (!(isset($_POST['retweet_account'])) && !(isset($_POST['retweet_message_id']))) {
        header('Location: twitter_home.php');
    }

    //ユーザ名/パスワード
    $retweet_account = htmlspecialchars($_POST['retweet_account'], ENT_QUOTES);
    $retweet_message_id = htmlspecialchars($_POST['retweet_message_id'], ENT_QUOTES);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>普通のリツイートか引用リツイートか選択するページ</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
</head>
<body>
    <!-- リツイートようページへ遷移 -->
    <form action="retweet.php" method="POST">
    <input type="hidden" name="retweet_account" value="<?php echo $retweet_account; ?>">
    <input type="hidden" name="retweet_message_id" value="<?php echo $retweet_message_id; ?>">
    <i class="fas fa-retweet"></i> リツイート<input type="submit" value="リツイート"><br>
    </form>

    <!-- 引用リツイート用ページへ遷移 -->
    <form action="retweet_comment.php" method="POST">
        <?php
    echo '<input type="hidden" name="retweet_account" value="'. $retweet_account . '">';
    echo '<input type="hidden" name="retweet_message_id" value="'.$retweet_message_id.'">';
    ?>
    <i class="fas fa-pen"></i> 引用リツイート<input type="submit" value="引用リツイート">
    </form>

    <!-- <?php echo $retweet_account . '<br>'; ?>
    <?php echo $retweet_message_id . '<br>'; ?> -->
    
</body>
</html>