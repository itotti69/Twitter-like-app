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
        //ORDER BYのうしろにDESCをつけるだけで表示メッセージの順序が新しい順になる。
        $sql = 'SELECT `following_name` FROM `follow_tb` WHERE `login_user_name` = "'. $_SESSION['account_name'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $following_name = $row['following_name'];
        //echo $following_name . "<br>";
    }

    if ($dbh) {
        //データベースへの問い合わせ
        //ORDER BYのうしろにDESCをつけるだけで表示メッセージの順序が新しい順になる。
        // $sql = 'SELECT * FROM `tweets_tb`  WHERE `account_name` = "'. $following_name . '"';
        $sql = 'SELECT * FROM `tweets_tb` ORDER BY `message_id` DESC';
        $sth = $dbh->query($sql); //SQLの実行
    }

    // while ($row = $sth->fetch()) {
    //     $message_id = $row['message_id'];
    // }

    //     if ($dbh) {
    //         //データベースへの問い合わせ
    //         $sql = 'SELECT `comment` FROM `comment_tb` WHERE `tweets_id` = "' . $message_id .'"';
    //         $sth = $dbh->query($sql); //SQLの実行
    //     }
    //     while ($row = $sth->fetch()) {  //瞬時呼び出し
    //         $comment = $row['comment'];
    //         //echo $following_name . "<br>";
    //     }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitterタイムライン画面</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
    <style>
        span {
            color: aqua;
        }
    </style>
</head>
<body>
    <!-- 課題２.記事は全員閲覧可能→タイムライン上に流れてくるのは全員が見ることのできる記事 -->
<?php if($_SESSION['lock_flag'] == 0): ?>
<div class=box>
<a href="menu_message.php" class="linkbutton">←メニュー</a>
    
<!-- 投票記事ここから -->
<table>
    <?php
    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT * FROM `vote_tb` ORDER BY `ID` DESC';
        $sth0 = $dbh->query($sql); //SQLの実行
    }

    echo '<td>' . '<hr>'  . '</td>';
    echo '<td>' . '<hr>'  . '</td>';
    echo '<td>' . '<hr>'  . '</td>';

    while($row = $sth0->fetch()) {
        //配列にデータを全て格納する必要がある
        echo '<tr>';
        //echo '<td>' . $count  . '</td>';
        echo '<td>'. $row['account_name'] . '</td>';
        echo '<td>' . $row['user_name'] . '</td>';
        echo '<td>' . $row['entry_date'] . '</td></tr>';
        echo '<tr><td>' . $row['message'] . '</td></tr>';
        // <?php echo '<form action="comment.php" method="POST">';
        echo '<tr><td>' . $row['message1'] . '</td>';
        echo '<td>' . $row['count1'] . '</td><td>';
        echo '<form action="vote_update.php" method="POST">
        <input type="hidden" name="vote_put1" value="1">'; ?>
           <div class="output-button">
                <button type="submit">送信</button>
            </div>
        <?php
        echo '</td></form></tr>'; 
        echo '<tr><td>' . $row['message2'] . '</td>';
        echo '<td>' . $row['count2'] . '</td><td>';
        echo '<form action="vote_update.php" method="POST">
        <input type="hidden" name="vote_put2" value="2">'; ?>
           <div class="output-button">
                <button type="submit">送信</button>
            </div>
        <?php
        echo '</td></form></tr>'; 
        echo '<tr><td>' . $row['message3'] . '</td>';
        echo '<td>' . $row['count3'] . '</td><td>';
        echo '<form action="vote_update.php" method="POST">
        <input type="hidden" name="vote_put3" value="3">'; ?>
           <div class="output-button">
                <button type="submit">送信</button>
            </div>
        <?php
        echo '</td></form></tr>'; 
        echo '</tr>';
        
        echo '<tr>';
        echo '<td>' . nl2br($row['message']) . '</td></tr>';

        echo '<tr>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '</tr>';  
    }
    ?>
    </table>
    <!-- 投票記事ここまで -->

    <table>
    <?php
    echo '<td>' . '<hr>'  . '</td>';
    echo '<td>' . '<hr>'  . '</td>';
    echo '<td>' . '<hr>'  . '</td>';
    while($row = $sth->fetch()) {
        //配列にデータを全て格納する必要がある
        
        echo '<tr>';
        //echo '<td>' . $count  . '</td>';
       echo '<td><div class="name_box"><div class="icon_image">' . '<img src="' . $row['icon_img'] . '">'. '</div>';
        echo '<div>' . $row['account_name'] . '</div></div></td>';
       
        echo '<td>' . $row['user_name'] . '</td>';
        echo '<td>' . $row['entry_date'] . '</td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<td>' . nl2br($row['message']) . '</td></tr>';?>
        <?php if(!($row['image'] == "") ): ?><?php
        echo '<tr><td><div class="image_box">' . '<img src="' . $row['image'] . '">'. '</div></td></tr>'; ?>
        <?php endif; ?>
        <?php echo '<form action="comment.php" method="POST">';
        echo '<td>'.
          '<input type="hidden" name="reply_id" value="' .
           $row['message_id'] . '"> . '?>
           <input type="submit"  value="返信">
           <?php echo '</td>';
        echo '</form>'; 
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

        echo '<tr>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '</tr>';  
    }
    
    ?>
    </table>

    <a href="twitter_home.php"><i class="fas fa-home fa-2x"></i></a>
    <a href="search.php"><i class="fas fa-search fa-2x"></i></a>
    
    <a href="write_message.php" class="linkbutton"><i class="fas fa-plus-circle fa-4x plus"></i></a>
    
</div>
<?php else: ?>
<h1>こちらのアカウントは、一時的に機能が制限されています。</h1><br>
<?php echo $_SESSION['account_name'] . '<br>'; ?>
<?php echo $_SESSION['user'] . '<br>'; ?>
<br>
<h2>問題の詳細</h2>
<p>ご利用のアカウントはTwitterの<span>次のポリシー</span>に違反している可能性があります。<br>
フォロー、いいね、リツイートが一定期間制限されます。</p>
<?php endif; ?>
</body>
</html>