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
        $sql = 'SELECT `private_flag`, `official_flag` FROM `users_tb` WHERE `account_name` = "' . $_SESSION['account_name'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
        $flag = 0;
    }

    while($row = $sth->fetch()) {
        $official_flag = $row['official_flag'];
        $private_flag = $row['private_flag'];
    }

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT * FROM `profile_tb` WHERE `profile_id` = "' . $_SESSION['user_id'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
        $flag = 0;
    }

    while($row = $sth->fetch()) {
        $icon_img = $row['icon_img'];
        $header_img = $row['header_img'];
        $intro = $row['intro'];
    }

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT * FROM `approval_tb` WHERE `private_account_name` = "' . $_SESSION['account_name'] . '" AND `flag` = 0';
        $sth = $dbh->query($sql); //SQLの実行
    }

    while($row = $sth->fetch()) {
        $private = $row['private_account_name'];
        $name = $row['name'];
    }

    if(isset($_POST['account'])) {
        if($_POST['account'] == $_SESSION['account_name']) {
            if ($dbh) {  //接続に成功した場合
                //データベースへの問い合わせSQL文（文字列）
                $sql = 'UPDATE `approval_tb` SET `flag` = 1 WHERE `private_account_name` = "' . $private . '"';
                //echo $sql;
                $sth = $dbh->query($sql);  //SQLの実行
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインユーザのプロフィール画面</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
</head>
<body>
<!-- 課題3. 投稿した自身のアカウントの記事のみ閲覧機能-->
<!-- ログインしているユーザのプロフィール画面 -->
<!-- 本人なので編集も可能 -->
<div class=box>
<a href="menu_message.php" class="linkbutton"><i class="fas fa-arrow-alt-circle-left"></i></a><br>

    
        <!-- 投稿した自身の記事だけ閲覧したい場合に切り替える -->
    <?php if(isset($header_img)): ?>
    <?php echo '<div class="header_image">' . '<img src="' . $header_img . '">'. '</div>'; ?><br>
    <?php endif; ?>
    <?php if(isset($icon_img)): ?>
    <?php echo '<div class="name_box"><div class="icon_image">' . '<img src="' . $icon_img . '">'. '</div>'; ?>
    <?php endif; ?>

    <?php echo '<div>' . $_SESSION['account_name'] . '</div></div>'?>
    <?php if($private_flag == 1): ?>
        <i class="fas fa-lock"></i><br>
    <?php endif; ?>
    <?php echo $_SESSION['user']; ?>

    <?php if($official_flag == 1): ?> 
    <img src="official.jpg" alt="">
   <?php endif; ?>

    <a href="edit.php" class="linkbutton">編集</a><br>
    

    <?php
        if(isset($name)) {
            echo $name . 'さんからのフォローリクエストが届いています。';
            echo '<form action="" method="POST">';
            echo '<input type="hidden" name="account" value="' . $_SESSION['account_name'] . '"></input>';
            echo '<input type="submit" value="許可"></input></form>';
        }
    ?>

    <table>
    ■ツイート
    <?php
    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT  *  FROM `tweets_tb` WHERE `account_name` = "' . $_SESSION['account_name'] . '" ORDER BY `message_id`';
        $sth = $dbh->query($sql); //SQLの実行
        $flag = 0;
    }
    echo '<tr>';
    echo '<td>' . '<hr>'  . '</td>';
    echo '<td>' . '<hr>'  . '</td>';
    echo '<td>' . '<hr>'  . '</td>';

    echo '</tr>'; 

    while($row = $sth->fetch()) {
        if ($row['user_name'] == $_SESSION['user']) {
            echo '<tr>';
            echo '<td>' . $row['account_name'] . '</td>';  //アカウント名
            echo '<td>' . $row['user_name'] . '</td>';  //ユーザー名
            echo '<td>' . $row['entry_date'] . '</td>';  //投稿された時間
            echo '</tr>';
            echo '<tr>';
            echo '<td>' . nl2br($row['message']) . '</td>';  //記事内容表示
            echo '</tr>'; ?>
            <?php if(!($row['image'] == "") ): ?><?php  //画像表示
                echo '<tr><td><div class="image_box">' . '<img src="' . $row['image'] . '">'. '</div></td></tr>'; ?>
                <?php endif; ?>
                <?php echo '<form action="comment.php" method="POST">';
                echo '<td>'.
                  '<input type="hidden" name="reply_id" value="' .
                   $row['message_id'] . '"> . '?>
                   <input type="submit"  value="返信">
                   <?php echo '</td>';
                echo '</form>'; 
                //いいね関連
                echo '<form action="like.php" method="POST">';
                echo '<tr><td>'. '<input type="hidden" name="like_account" value="'.
                 $_SESSION['account_name'] . '">'.
                  '<input type="hidden" name="like_message_id" value="' .
                   $row['message_id'] . '"> . '?>
                   <?php if($row['like_push'] == 0): ?>
                   <input type="submit" class="heart" value="いいね">
                   <?php echo "いいね"; ?>
                   <?php elseif($row['like_push'] == 1): ?>
                    <input type="submit" class="heart-plus" value="いいね">
                    <?php echo "いいね"; ?>
                    <?php endif; ?>
                   <?php echo '</td>';
                echo '</form>'; 
                echo '<td>' . $row['like_counts'] . '</td>';
                //リツイート関連 
                echo '<form action="retweet_select.php" method="POST">';
                echo '<td>'. '<input type="hidden" name="retweet_account" value="'.
                 $_SESSION['account_name'] . '">'.
                  '<input type="hidden" name="retweet_message_id" value="' .
                   $row['message_id'] . '"> . '?>
                   <?php if($row['retweet_push'] == 0): ?>
                   <input type="submit" class="retweet-off" value="リツイート">
                   <?php echo "リツイート"; ?>
                   <?php elseif($row['retweet_push'] == 1): ?>
                    <input type="submit" class="retweet-on" value="リツイート">
                    <?php echo "リツイート"; ?>
                    <?php endif; ?>
                   <?php echo '</td>';
                echo '</form>'; 
                echo '<td>' . $row['retweet_count'] . '</td>';
                echo '</tr>'; 
                echo '<tr><td>' . '緯度: '.$row['latitude'] . '°</td>';
                echo '<td>' . '経度: ' . $row['longitude'] . '°</td></tr>';
                if (!($row['reply'] == "")) {
                    echo '<td>';
                    echo '↪︎' . $row['reply'] ;
                    echo '</td>'; 
                }
            
            echo '<td>' . '<hr>'  . '</td>';
            echo '<td>' . '<hr>'  . '</td>';
            echo '<td>' . '<hr>'  . '</td>';
        }else{

        }
    }

    
    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT * FROM `retweet_tb` WHERE `user_id` = "' . $_SESSION['user_id'] . '" ORDER BY `entry_date`';
        $sth = $dbh->query($sql); //SQLの実行
    }
    
    echo '<table class="retweet_box_outside">';
    echo '<tr>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '</tr>';  
    while($row = $sth->fetch()) {
        //配列にデータを全て格納する必要がある
        echo '<tr>';
        echo '<リツイート済み><br>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>' . $_SESSION['account_name'] . '</td>';
        echo '</tr><br>';

        echo '<tr>';
        echo '<td>' . $_SESSION['user'] . '</td>';
        echo '</tr>';

        if (!($row['comment'] == "")){
            echo '<tr>';
            echo '<br><td>' . $row['comment'] . '</td>';
            echo '</tr>';
        }

        echo '<table class="retweet_box_inside">';
        echo '<tr>';
        echo '<td>' . $row['account_name'] . '</td>';
        echo '<td class="user">' . $row['user_name']  . '</td>';
        echo '<td>' . $row['entry_date'] . '</td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<td>' . nl2br($row['message']) . '</td>';
        echo '<form action="like.php" method="POST">';
        echo '</tr>';  

        echo '<tr>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '</tr>';  
        echo '</table>';  
    }
    echo '</table>';
    
    ?>
    </table>
   

    ■いいね
    <?php 
    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `like_id`, `account_name`, `user_name` , `entry_date` , `message`  
        FROM `like_tb` WHERE `like_push_user` = "' . $_SESSION['account_name'] . '" ORDER BY `entry_date`';
        $sth = $dbh->query($sql); //SQLの実行
    }
    
    echo '<table>';
    echo '<tr>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '</tr>';  
    while($row = $sth->fetch()) {
        //配列にデータを全て格納する必要がある
        echo '<tr>';
        echo '<td>' . $row['account_name'] . '</td>';
       
        echo '<td>' . $row['user_name'] . '</td>';
        echo '<td>' . $row['entry_date'] . '</td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<td>' . nl2br($row['message']) . '</td>';
        echo '<form action="like.php" method="POST">';
        echo '</tr>';  

        echo '<tr>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '</tr>';  
    }
    echo '</table>';
    ?>



    ■フォロー中<br>
    <?php 
    if ($dbh) {
        //データベースへの問い合わせ
        //ORDER BYのうしろにDESCをつけるだけで表示メッセージの順序が新しい順になる。
        $sql = 'SELECT `following_name` FROM `follow_tb` WHERE `login_user_name` = "'. $_SESSION['account_name'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    
    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $following_name = $row['following_name'];
        echo $following_name . "<br>";
    }

    $login_user_name = "";

    //フォロワーをチェックする
    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `login_user_name` FROM `follow_tb` WHERE `following_name` = "'. $_SESSION['account_name'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    //5. フォロワー一覧表示
    echo "■フォロワー" . "<br>";
    while ($row = $sth->fetch()) {  //瞬時呼び出し
        //echo $row['login_user_id'];
        $login_user_name = $row['login_user_name'];
        echo $login_user_name . "<br>";
        //echo $login_user_name . '<br>';
    }
    ?>

    <a href="profile_about.php">自身とフォロワーの記事のみ閲覧ページへ</a>

    <div class="back">
    <a href="write_message.php" class="linkbutton"><i class="fas fa-plus-circle fa-4x plus"></i></a>
    </div>
</div>
</body>
</html>
