<?php
session_start();

//接続用の関数の呼び出し
require_once(__DIR__ . '/functions.php');
//書き込みがあるかどうかの確認
if (!(isset($_POST['reply_id']))) {
    echo '失敗!!';
}

//DBへの接続
$dbh = connectDB();
if ($dbh) {
    //データベースへの問い合わせ
    $sql = 'SELECT * FROM `profile_tb` WHERE `profile_id` = "' . $_SESSION['user_id'] . '"';
    $sth = $dbh->query($sql); //SQLの実行
    $flag = 0;
}

while($row = $sth->fetch()) {
    $icon_img = $row['icon_img'];
}

//返信記事ID
$reply_id = htmlspecialchars($_POST['reply_id'], ENT_QUOTES);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>返信メッセージ書き込み</title>
</head>
<body>
    <?php echo $reply_id; ?>
    <form action="comment_insert.php" method="POST" enctype="multipart/form-data">
    <a href="twitter_home.php">キャンセル</a>
    <input type="submit" value="返信"><br>
    <?php if(isset($icon_img)): ?>
    <?php echo '<div class="name_box"><div class="icon_image">' . '<img src="' . $icon_img . '">'. '</div>'; ?>
    <?php endif; ?>
    <input type="hidden" name="reply_id" value="<?php echo $reply_id; ?>">
    <textarea name="reply_comment" cols="40" rows="5" placeholder="返信をツイート"></textarea>
    </form>
</body>
</html>