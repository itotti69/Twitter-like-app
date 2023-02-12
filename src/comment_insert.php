<?php
    session_start();

    //ログインの確認
    if (!((isset($POST['reply_id']) && isset($POST['reply_comment'])))) {
        //ログインフォームへ
        echo '失敗!!';
        //header('Location: twitter_home.php');
    }

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //ユーザ名/パスワード
    $reply_id = htmlspecialchars($_POST['reply_id'], ENT_QUOTES);
    $reply_comment = htmlspecialchars($_POST['reply_comment'], ENT_QUOTES);

    //DBへの接続
    $dbh = connectDB();

   
// //ファイル名の取り出し
// $file_name = $_FILES['upload_name']['name'];

// //echo '$file_name' . '<br>';

// $file_type = $_FILES['upload_name']['type'];
// //echo '$file_type' . '<br>';
// //一時ファイル名の取り出し
// $temp_name = $_FILES['upload_name']['tmp_name'];
// //echo '$temp_name' . '<br>';

// //保存先のディレクトリ
// $dir = 'upload/';
// //保存先のファイル名
// $upload_name = $dir . $file_name;

// //JPEG形式のファイルをアップロードする
// //JPEG形式のファイルをアップロードする
// if (($file_type == 'image/jpeg') ||
// ($file_type == 'image/pjpeg') ||
// ($file_type == 'image/png')) {
//     //アップロード（移動）
//     $result = move_uploaded_file($temp_name, $upload_name);

    
    
//     if ($result) {
//         //アップロードの成功
//         echo 'アップロードの成功' . "<br>";
//     } else {                                                                                                                                                          
//         //アップロードの失敗
//         echo 'アップロードの失敗' . "<br>";   
//     }
// } else {
//     //JPEG形式以外のファイルの対応
//     echo 'JPEG/PNG形式の画像をアップロードしてください' . "<br>";
// }

// //以下scandirを使わないで、直接file名を指定して取り出す
// //ファイル情報を取り出す
// $file_info = pathinfo ($upload_name);

// //ファイル名
// $file_name = $dir . $file_info['basename'];
// echo $file_name;

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'INSERT INTO `comment_tb`(`tweets_id`,`comment`) VALUES ("' . $reply_id . '","' . 
        $reply_comment . '")';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行
    }

    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'UPDATE `tweets_tb` SET `reply` = "' . $reply_comment . '" WHERE `message_id` = "' . $reply_id . '"';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>返信コメントDB挿入処理</title>
</head>
<body>
<?php
    if ($sth == FALSE) {
        echo '■入力に失敗しています。';
    }else{ 
        //ログイン成功ならtwitter_home.phpにジャンプ
        header('Location: twitter_home.php');
    }
    ?>
</body>
</html>