<?php
    //セッション生成
    session_start();

    //接続用関数の呼び出し
    require_once(__DIR__.'/functions.php');

    //DBへの接続
    $dbh = connectDB();

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `login_user_name` FROM `follow_tb` WHERE `following_name` = "' . $_SESSION['account_name'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    while($row = $sth->fetch()) {
        $login_user_name = $row['login_user_name'];
    }

    if(isset($login_user_name)) {
        if ($dbh) {
            //データベースへの問い合わせ
            $sql = 'SELECT * FROM `tweets_tb` WHERE `account_name` = "' . $login_user_name . '" OR `account_name` = "' . $_SESSION['account_name'] . '"';
            $sth = $dbh->query($sql); //SQLの実行
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
<a href="profile.php">プロフィールへ</a>
<p>ログインユーザー→<?php echo $_SESSION['account_name']; ?></p>
<table>
    <?php
    if(isset($login_user_name)) {
        if ($dbh) {
            //データベースへの問い合わせ
            $sql = 'SELECT * FROM `vote_tb` WHERE `account_name` = "' . $login_user_name . '" OR `account_name` = "' . $_SESSION['account_name'] . '" ORDER BY `ID` DESC';
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
</body>
</html>