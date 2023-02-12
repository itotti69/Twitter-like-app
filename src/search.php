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
        //ORDER BYのうしろにDESCをつけるだけで表示メッセージの順序が新しい順になる。
        $sql = 'SELECT `user_id`, `account_name`, `user_name` FROM `users_tb` WHERE `user_id` !=  "' . $_SESSION['user_id'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
        $flag = 0;
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索画面</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
</head>
<body>

    <a href="twitter_home.php" class="linkbutton"><i class="fas fa-arrow-alt-circle-left"></i></a><br>
    
    <!-- ユーザーの検索を行う -->
    <div>
        <form action="" method="POST">
        ユーザーの検索: <input name="users" placeholder="🔍キーワード検索"><br>
        <input type="submit" value="検索"></button>
        </form>
    </div>

    <?php
    if(isset($_POST['users'])) {
        if ($dbh) {
            //データベースへの問い合わせSQL文（文字列）
            $sql = 'SELECT * FROM `users_tb` WHERE `account_name`
                    LIKE "%' . $_POST['users'] . '%"';
            $sth = $dbh->query($sql);  //SQLの実行
            //配列の宣言
            while ($row = $sth->fetch()) {  //瞬時呼び出し
                echo '<table><tr>';
                echo '<td>' . $row['account_name'] . '</td>';
                echo '<td>' . $row['user_name'] . '</td>';
                echo '<form action="profile_ex.php" method="POST">';
                echo '<td>'. '<input type="hidden" name="profile_name" value="'.
                $row['account_name']. '"> . '?>
                <input type="submit" value="プロフィール"><i class="fas fa-user-alt"></i>
                <?php echo '</td>';
                echo '</form>'; 
                echo '</tr>';  
                echo '<tr>';
                echo '<td>' . '<hr>'  . '</td>';
                echo '<td>' . '<hr>'  . '</td>';
                echo '<td>' . '<hr>'  . '</td>';
                echo '<td>' . '<hr>'  . '</td>';
                echo '</tr></table>';    
            } 
        }  //$dbh
    }
            
        ?>

    <div>
        投稿の検索: <input id="search_message"><br>
    </div>
 
<table id="all_show_tweet">
</table>

<table id="all_show_result">
</table>

<script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
<script src="js/getData.js"></script>
<script src="js/message.js"></script>
</body>
</html>
