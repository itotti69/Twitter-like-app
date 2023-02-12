<?php
session_start();

//接続用の関数の呼び出し
require_once(__DIR__ . '/functions.php');

//書き込まれた文章を変数に代入
$vote_put1 = htmlspecialchars($_POST['vote_put1'], ENT_QUOTES);
$vote_put2 = htmlspecialchars($_POST['vote_put2'], ENT_QUOTES);
$vote_put3 = htmlspecialchars($_POST['vote_put3'], ENT_QUOTES);

//DBへの接続
$dbh = connectDB(); 

if($vote_put1 == 1) {
    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'UPDATE `vote_tb` SET `count1` = `count1` + 1';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行

        header('Location: twitter_home.php');
    }
} 

if($vote_put2 == 2) {
    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'UPDATE `vote_tb` SET `count2` = `count2` + 1';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行

        header('Location: twitter_home.php');
    }
} 

if($vote_put3 == 3) {
    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'UPDATE `vote_tb` SET `count3` = `count3` + 1';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行

        header('Location: twitter_home.php');
    }
} else {
    header('Location: twitter_home.php');
}

?>