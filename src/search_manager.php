<?php
//セッション生成
session_start();

if(!((isset($_SESSION['login']) && $_SESSION['login'] == 'OK'))){
    //チェック
    header('Location: login.html');
}

//接続用関数の呼び出し
require_once(__DIR__.'/functions.php');

$search_users = htmlspecialchars($_POST['search_users'], ENT_QUOTES);

//DBへの接続
$dbh = connectDB();

if ($dbh) {
    //データベースへの問い合わせSQL文（文字列）
    //ユーザ名を部分一致で取得
    //30. ユーザの検索
    $sql = 'SELECT `user_id`, `account_name`, `user_name` FROM `users_tb` WHERE `user_name`
            LIKE "%' . $search_users . '%"';
    $sth = $dbh->query($sql);  //SQLの実行

    //配列の宣言
    //HTMLに渡すため
    $productList = array();

    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $productList[] = array(
            'user_id'=>$row['user_id'],
            'account_name'=>$row['account_name'],
            'user_name'=>$row['user_name']
        );
    }

    //ヘッダーを指定することによりjsonの動作を安定させる
    header('Content-type: application/json');
    echo json_encode($productList);
    // echo json_encode($productList2);
}  //$dbh
?>
