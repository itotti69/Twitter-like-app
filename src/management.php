<?php
    session_start();

    //ログインの確認
    if (!((isset($_SESSION['login']) && $_SESSION['login'] == 'OK'))) {
        //ログインフォームへ
        header('Location: login.html');
    }

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //DBへの接続
    $dbh = connectDB();

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `user_id`, `account_name`, `user_name`, `official_flag`, `lock_flag` FROM `users_tb` WHERE `user_id` !=  "' . $_SESSION['user_id'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウントの凍結＆公認バッジの付与を行う</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
</head>
<body>
    <!-- 19. 管理者による公認アカウントバッジ付与 -->
     <!-- 20. 管理者によるアカウントの凍結 -->
<a href="menu_message.php" class="linkbutton"><i class="fas fa-arrow-alt-circle-left"></i></a><br>
    こちらは管理者ページです。アカウントの凍結や公認バッジの付与を行えます。
<table id="user_list">
<?php
while($row = $sth->fetch()) {
        echo '<tr>';
        echo '<td>' . $row['account_name'] . '</td>';
        echo '<td>' . $row['user_name'] . '</td>';
        echo '<form action="lock.php" method="POST">';
        echo '<td>'. '<input type="hidden" name="lock_id" value="'.
        $row['user_id']. '"> . '?>
        <?php if($row['lock_flag'] == 0): ?>
           <input type="submit" value="アカウント凍結"><i class="fas fa-user-alt"></i>
           <?php else: ?>
            <input type="submit" value="アカウント凍結解除"><i class="fas fa-user-alt"></i>
            <?php endif; ?>
           <?php echo '</td>';
        echo '</form>'; 
        echo '<form action="official.php" method="POST">';
        echo '<td>'. '<input type="hidden" name="official_id" value="'.
        $row['user_id']. '"> . '?>
        <?php if($row['official_flag'] == 0): ?>
           <input type="submit" value="公認アカウントにする"><i class="fas fa-user-alt"></i>
           <?php else: ?>
            <input type="submit" value="公認アカウント解除"><i class="fas fa-user-alt"></i>
            <?php endif; ?>
           <?php echo '</td>';
        echo '</form>'; 
        echo '</tr>';  
        echo '<tr>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '<td>' . '<hr>'  . '</td>';
        echo '</tr>';    
    }
    ?>
</body>
</html>