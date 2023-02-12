<?php
//セッション生成
session_start();

//接続用関数の呼び出し
require_once(__DIR__.'/functions.php');

//DBへの接続
$dbh = connectDB();

if ($dbh) {
    //データベースへの問い合わせ
    $sql = 'SELECT `private_flag` FROM `users_tb` WHERE `user_id` = "'. $_SESSION['user_id'] . '"';
    $sth = $dbh->query($sql); //SQLの実行
}

while ($row = $sth->fetch()) {  //瞬時呼び出し
    $private_flag = $row['private_flag'];
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>非公開アカウント設定ページ</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
</head>
<body>
    <!-- 7. 鍵付きアカウントの作成 -->
    <!-- 鍵付きアカウントにするかしないか自分で選択できる -->
<a href="menu_message.php" class="linkbutton"><i class="fas fa-arrow-alt-circle-left"></i></a><br>
    Twitterアクティビティ<br>
    <hr>
    <i class="fas fa-users"></i>オーディエンスとタグ付け<br>
    <p>Twitterで他のユーザーに表示する情報を管理します。</p>
    <hr>
    
    <form action="private_insert.php" method="POST">
    <?php echo '<input type="hidden" name="private_id" value="'. $_SESSION['user_id']. '">'; ?>
    <?php if($private_flag == 0): ?>
    ツイートを非公開にする
    <input type="submit" class="private-off" value="非公開">
    <?php elseif($private_flag == 1): ?>
    ツイートを公開にする
    <input type="submit" class="private-on" value="公開">
    <?php endif; ?>
    </form>
    <p>ツイートをフォロワーのみに表示します。この設定をオンにすると、<br>
        今後は新しいフォロワーを1人ずつ許可する必要があります。</p>

    <hr>
    <h3><a href="login.html">ログアウト</a></h3>
</body>
</html>