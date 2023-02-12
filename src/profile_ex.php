<?php
    //セッション生成
    session_start();

    //profile_idは他人
    if(!((isset($_POST['profile_name'])))){
        //チェック
        header('Location: search.php');
    }

    $profile_name = htmlspecialchars($_POST['profile_name'], ENT_QUOTES);
    
    //接続用関数の呼び出し
    require_once(__DIR__.'/functions.php');

    //DBへの接続
    $dbh = connectDB();

    //公開するかどうかのフラグ
    $flag = 0;

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `account_name`, `user_name`, `official_flag`  FROM `users_tb` WHERE `account_name` = "' . $_POST['profile_name'] .'"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    //echo $_SESSION['user'];
    while($row = $sth->fetch()) {   
        $account_name = $row['account_name'];
        $user_name = $row['user_name'];
        $official_flag = $row['official_flag'];
    }

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `account_name`, `private_flag` FROM `users_tb`
         WHERE `account_name` = "' . $_POST['profile_name'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    while($row = $sth->fetch()) {   
        $account_name = $row['account_name'];
        $private_flag = $row['private_flag'];
    }

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `login_user_name`, `following_name`, `follow_flag` FROM `follow_tb`
         WHERE `login_user_name` = "' . $_SESSION['account_name'] . '" AND `following_name` = "'. $account_name . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    while($row = $sth->fetch()) {   
        $follow_flag = $row['follow_flag'];
        $following_name = $row['following_name'];
        $login_user_name = $row['login_user_name'];
    }

    //$private_flag == 1なら　
    //$_POST['profile_id']のアカウント名=following_name && login_user_id == $_SESSION['user_id']の時だけ表示させる
    if ($private_flag == 1) {
        if(isset($following_name)) {
            if(($account_name == $following_name) && ($login_user_name == $_SESSION['account_name'])) {
                $flag = 0;
            }else{
                $flag = 1;
            }
        }else{
            $flag = 1;
        }
    } else { //$private_flag == 0なら全員公開
        $flag = 0;
    }

    $set = empty($follow_flag);

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `name` FROM `approval_tb`
         WHERE `private_account_name` = "' . $profile_name . '" AND `flag` = 1';
        $sth = $dbh->query($sql); //SQLの実行
    }

    while($row = $sth->fetch()) {   
        $name = $row['name'];
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>他のユーザのプロフィール画面</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
</head>
<body>
<!-- ログインしている人　または　自身以外の人のプロフィール画面 -->
<!-- ユーザアイコンクリックで基本的にこのページへ移動 -->
<!-- プロフィールの編集は不可 -->

<div class=box>
    <a href="search.php" class="linkbutton"><i class="fas fa-arrow-alt-circle-left"></i></a><br>
    <?php if($flag == 0 || $_SESSION['account_name'] == $name): ?>
        <!-- ツイートを閲覧できます -->
    <form action="follow_managar.php" method="POST">
        <?php echo $account_name; ?>

        <?php if($private_flag == 1): ?>
        <i class="fas fa-lock"></i><br>
        <?php endif; ?>

        <?php echo $user_name; ?>
        <?php if($official_flag == 1): ?> 

        <img src="official.jpg" alt="">
        <?php endif; ?>
        
        <input type="hidden" name="follow_name" value="<?php echo $_POST['profile_name']; ?>">

        <?php if(isset($follow_flag)): ?>
        <input type="submit"   value="フォロー中">
        <?php elseif(!isset($follow_flag)): ?>
            <input type="submit"   value="フォローする">
        <?php endif; ?>
    </form>
    

    <table>
    ■ツイート
    <?php
    echo '<tr>';
    echo '<td>' . '<hr>'  . '</td>';
    echo '<td>' . '<hr>'  . '</td>';
    echo '<td>' . '<hr>'  . '</td>';

    echo '</tr>';
    
    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `account_name` FROM `users_tb` WHERE `account_name` = "' . $_POST['profile_name'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }   

    while($row = $sth->fetch()) {
        $account = $row['account_name'];
    }

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT * FROM `tweets_tb` WHERE `account_name` = "' . $account .'"';
        $sth = $dbh->query($sql); //SQLの実行
    }


    //echo $_SESSION['user'];
    while($row = $sth->fetch()) {
        //echo $row['user_name'];    
            //配列にデータを全て格納する必要がある
            echo '<tr>';
            echo '<td>' . $account_name . '</td>';
            echo '<td>' . $user_name . '</td>';
            echo '<td>' . $row['entry_date'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>' . nl2br($row['message']) . '</td>';
            echo '</tr>';
            
            echo '<td>' . '<hr>'  . '</td>';
            echo '<td>' . '<hr>'  . '</td>';
            echo '<td>' . '<hr>'  . '</td>';
    }

    
    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `account_name`, `user_name` , `entry_date` , `message`  
        FROM `retweet_tb` WHERE `account_name` = "' . $_POST['profile_name'] . '" ORDER BY `entry_date`';
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
        echo '<リツイート済み>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>' . $_SESSION['account_name'] . '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>' . $_SESSION['user'] . '</td>';
        echo '</tr>';

        echo '<table class="retweet_box_inside">';
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
        echo '</table>';  
    }
    echo '</table>';
    
    ?>
    

    ■いいね<br>
    <?php 

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `like_id`, `account_name`, `user_name` , `entry_date` , `message`  
        FROM `like_tb` WHERE `like_push_user` = "' . $account . '" ORDER BY `entry_date`';
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


    <!-- <?php echo $_POST['profile']; ?> -->
    ■フォロー中<br>
    <?php 
    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT `following_name` FROM `follow_tb` WHERE `login_user_name` = "'. $_POST['profile_name'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    
    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $following_name = $row['following_name'];
        echo $following_name . "<br>";
    }

    if ($dbh) {
        //データベースへの問い合わせ
        //ORDER BYのうしろにDESCをつけるだけで表示メッセージの順序が新しい順になる。
        $sql = 'SELECT `login_user_name` FROM `follow_tb` WHERE `following_name` = "'. $_SESSION['account_name'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $login_user_name = $row['login_user_name'];
    }

    if (isset($login_user_name)) {
        if ($dbh) {
            //データベースへの問い合わせ
            //ORDER BYのうしろにDESCをつけるだけで表示メッセージの順序が新しい順になる。
            $sql = 'SELECT `account_name` FROM `users_tb` WHERE `account_name` = "'. $login_user_name . '"';
            $sth = $dbh->query($sql); //SQLの実行
        }
    }
    

    echo "■フォロワー" . "<br>";

    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $account_name = $row['account_name'];

        echo $account_name;
    }

    ?>
<a href="write_message.php" class="linkbutton"><i class="fas fa-plus-circle fa-4x plus"></i></a>
<?php else: ?>
        <h1>ツイートは非公開です。</h1><br>

        <p><?php echo $user_name; ?>から承認された場合のみ<br>
        ツイートやプロフィールの表示ができます。[フォローする] <br>
        をタップすると承認リクエストが送信されます。</p>
        <form action="approval.php" method="POST">
            <p>承認申請はこちらから↓</p>
            
            <input type="hidden" name="login_user" value="<?php echo $profile_name; ?>">
            <input type="submit" value="承認申請">
        </form>
<?php endif; ?>
</div>

</body>
</html>