<?php
session_start();

//接続用の関数の呼び出し
require_once(__DIR__ . '/functions.php');

$input1 = htmlspecialchars($_POST['input1'], ENT_QUOTES);
$input2 = htmlspecialchars($_POST['input2'], ENT_QUOTES);

if(!((isset($_POST['input1'])))){
    //チェック
   echo '失敗';
}

if(!((isset($_POST['input2'])))){
    //チェック
   echo '失敗';
}

//DBへの接続
$dbh = connectDB();

// echo 'あ';
// echo $input1 . '<br>';
// echo $input2 . '<br>';


//緯度・経度のデータが格納されたMySQLデータベースがあり、ある地点（緯度・経度）から近い順に並び替えて取得するには、次のようなSQLを使います。

if ($dbh) {
    //データベースへの問い合わせ
   //現在地と等しいものだけが表示される（同じ場所で投稿したものだけ見れる。
   //逆に言えば、その書き込みがあった場所に行かなければ閲覧できないシークレット機能）
    $sql = 'SELECT * FROM `tweets_tb` WHERE `latitude` = "' . $input1 . '" AND `longitude` = "' . $input2 . '"';
    $sth = $dbh->query($sql); //SQLの実行
    // while($row = $sth->fetch()) {
    //     echo $row['latitude'];
    //     echo $row['longitude'];
    // }
}
 ?>

 <!DOCTYPE html>
 <html lang="ja">
 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>位置情報を基にした記事の閲覧2</title>
     <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
 </head>
 <body>
     <p>現在の場所で過去に投稿された記事はこちらです↓
     </p>
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
    <div class="back">
    <a href="location_show.php" class="linkbutton"><i class="fas fa-arrow-alt-circle-left"></i></a>
    </div>
 </body>
 </html>

