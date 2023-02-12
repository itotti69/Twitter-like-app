<?php
    //セッション生成
    session_start();

    if(!((isset($_SESSION['login']) && $_SESSION['login'] == 'OK'))){
        //チェック
        header('Location: login.html');
    }

    //接続用関数の呼び出し
    require_once(__DIR__.'/functions.php');

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

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT * FROM `users_tb` WHERE `user_id` = "' . $_SESSION['user_id'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    while($row = $sth->fetch()) {
        $private_flag = $row['private_flag'];
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウントメニュー</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
</head>
<body>
    <!-- メニュー画面管理者用画面と一般ユーザ画面で分けている -->
    <hr>
    <?php if(isset($icon_img)): ?>
    <!-- <a href="logout.php">【ログアウト】</a> -->
    <?php echo '<div class="name_box"><div class="icon_image">' . '<img src="' . $icon_img . '">'. '</div>'; ?>
    <?php echo '<div>' . $_SESSION['account_name'] . '</div></div>'?>
    <?php else: ?>
    <?php echo '<div>' . $_SESSION['account_name'] . '</div>'?>
    <?php endif; ?>
    <?php if($private_flag == 1): ?>
        <i class="fas fa-lock"></i><br>
    <?php endif; ?>
    <?php echo $_SESSION['user']; ?>
    <hr>

    <!-- <?php echo $_SESSION['twitter_flag'] ?> -->

    <!-- 管理者がログインしている場合 -->
    <?php if ($_SESSION['twitter_flag'] == "0"): ?>
    <ul>
        <a href="profile.php">
        <li>
        <i class="fas fa-user-alt"></i>
        プロフィール
        </li>
        </a>
        
        <a href="management.php">
        <li>
            <i class="fas fa-star"></i>
            管理者ページ
        </li>
        </a>

        <a href="private.php">
        <li>
            <i class="fas fa-cog"></i>
            設定とプライバシー
        </li>
        </a>

        <a href="location_show.php">
        <li>
            位置情報を基にした記事閲覧ページ
        </li>
        </a>
    </ul>
    <?php endif; ?>

     <!-- 一般ユーザがログインしている場合 -->
     <!-- 削除機能である「メッセージの管理」リンクを新たに追加 -->
    <?php if ($_SESSION['twitter_flag'] == "1"): ?>
    <ul>
        <a href="profile.php">
        <li>
        <i class="fas fa-user-alt"></i>
            プロフィール
        </li>
        </a>

        <a href="private.php">
        <li>
            <i class="fas fa-cog"></i>
            設定とプライバシー
        </li>
        </a>

        <a href="location_show.php">
        <li>
            位置情報を基にした記事閲覧ページ
        </li>
        </a>
    </ul>
    <?php endif; ?>

    <a href="twitter_home.php"><i class="fas fa-home"></i>HOME</a>
    
</body>
</html>
