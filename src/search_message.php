<?php
//セッション生成
session_start();

if(!((isset($_SESSION['login']) && $_SESSION['login'] == 'OK'))){
    //チェック
    header('Location: login.html');
}

//接続用関数の呼び出し
require_once(__DIR__.'/functions.php');

$search_message = htmlspecialchars($_POST['search_message'], ENT_QUOTES);

//DBへの接続
$dbh = connectDB();

if ($dbh) {
    //データベースへの問い合わせSQL文（文字列）
    //記事を部分一致で取得
    //29. 記事の検索
    $sql = 'SELECT * FROM `tweets_tb` WHERE `message`
            LIKE "%' . $search_message . '%"';
    $sth = $dbh->query($sql);  //SQLの実行

    //配列の宣言
    //HTMLに渡すため
    $productList2 = array();

    while ($row = $sth->fetch()) {  //瞬時呼び出し
        $productList2[] = array(
            'account_name'=>$row['account_name'],
            'user_name'=>$row['user_name'],
            'entry_date'=>$row['entry_date'],
            //改行コードの処理\n => <br>
            'message'=>nl2br($row['message']),
        );
    }

    //ヘッダーを指定することによりjsonの動作を安定させる
    header('Content-type: application/json');
    echo json_encode($productList2);
    // echo json_encode($productList2);
}  //$dbh
?>
