<?php
    session_start();

    //ログインの確認
    if (!((isset($_SESSION['login']) && $_SESSION['login'] == 'OK'))) {
        //ログインフォームへ
        header('Location: login.html');
    }

    //接続用の関数の呼び出し
    require_once(__DIR__ . '/functions.php');

    //書き込みがあるかどうかの確認
    if (!(isset($_POST['message']))) {
        header('Location: twitter_home.php');
    }
    
    $input1 = htmlspecialchars($_POST['input1'], ENT_QUOTES);
    $input2 = htmlspecialchars($_POST['input2'], ENT_QUOTES);

    // if (!(isset($_POST['input1']))) {
    //     echo '失敗しました。';
    // } else {
    //     echo '成功しました。';
    // }


    // if (!(isset($_POST['input2']))) {
    //     echo '失敗しました。';
    // } else {
    //     echo '成功しました。';
    // }

    // echo $input1 . '<br>';
    // echo $input2 . '<br>';

    //書き込まれた文章を変数に代入
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES);
    //DBへの接続
    $dbh = connectDB();
    
    //echo 'mb_strlen($message)';
   
    //ファイル名の取り出し
    $file_name = $_FILES['upload_name']['name'];

    //echo '$file_name' . '<br>';

    $file_type = $_FILES['upload_name']['type'];
    //echo '$file_type' . '<br>';
    //一時ファイル名の取り出し
    $temp_name = $_FILES['upload_name']['tmp_name'];
    //echo '$temp_name' . '<br>';

    //保存先のディレクトリ
    $dir = 'upload/';
    //保存先のファイル名
    $upload_name = $dir . $file_name;

    //JPEG形式のファイルをアップロードする
    //JPEG形式のファイルをアップロードする
    if (($file_type == 'image/jpeg') ||
    ($file_type == 'image/pjpeg') ||
    ($file_type == 'image/png')) {
        //アップロード（移動）
        $result = move_uploaded_file($temp_name, $upload_name);

        if ($result) {
            //アップロードの成功
            echo 'アップロードの成功' . "<br>";
        } else {                                                                                                                                                          
            //アップロードの失敗
            echo 'アップロードの失敗' . "<br>";   
        }
    } else {
        //JPEG形式以外のファイルの対応
        echo 'JPEG/PNG形式の画像をアップロードしてください' . "<br>";
    }

    //ファイル情報を取り出す
    $file_info = pathinfo ($upload_name);

    //ファイル名
    $file_name = $dir . $file_info['basename'];
    //echo $file_name;

    $follow_flag = 0;
    $like_push = 0;
    $retweet_push = 0;
    $like_counts = 0;
    $retweet_count = 0;
    $reply = "";
    $str_check = 0;

    if ((isset($input1))) {
        $latitude = $input1;
    } else {
        $latitude = "";
    }

    if ((isset($input2))) {
        $longitude = $input2;
    } else {
        $longitude = "";
    }

    if ($dbh) {
        //データベースへの問い合わせ
        $sql = 'SELECT * FROM `profile_tb` WHERE `profile_id` = "' . $_SESSION['user_id'] . '"';
        $sth = $dbh->query($sql); //SQLの実行
    }

    while($row = $sth->fetch()) {
        $icon_img = $row['icon_img'];
    }

    //文字数の上限１４０文字に設定
    $limit = 140;

    if (mb_strlen($message) > $limit) {
        $str_check = 1;
    }else{
        if(isset($icon_img)) {
            if ($dbh) {  //接続に成功した場合
                //データベースへの問い合わせSQL文（文字列）
                $sql = 'INSERT INTO `tweets_tb`(`icon_img`, `account_name`,`user_name`,`message`,`reply`, `image`, `follow_flag`, `like_push`, `retweet_push`,`like_counts`, `retweet_count`, `str_check`, `latitude`, `longitude`) VALUES ("' . $icon_img . '","' . $_SESSION['account_name'] . '","' . 
                $_SESSION['user'] . '","' . $message . '","' . $reply . '", "' . $file_name . '", "' . $follow_flag . '", "' . $like_push . '","' . $retweet_push . '", "' .$like_counts . '","' . $retweet_count . '", "' . $str_check . '", "' . $latitude . '", "' . $longitude . '")';
                //echo $sql;
                $sth = $dbh->query($sql);  //SQLの実行
            }
        }else{
            if ($dbh) {  //接続に成功した場合
                $icon_img = "";
                //データベースへの問い合わせSQL文（文字列）
                $sql = 'INSERT INTO `tweets_tb`(`icon_img`, `account_name`,`user_name`,`message`,`reply`, `image`, `follow_flag`, `like_push`, `retweet_push`,`like_counts`, `retweet_count`, `str_check`, `latitude`, `longitude`) VALUES ("' . $icon_img . '","' . $_SESSION['account_name'] . '","' . 
                $_SESSION['user'] . '","' . $message . '","' . $reply . '", "' . $file_name . '", "' . $follow_flag . '", "' . $like_push . '","' . $retweet_push . '", "' .$like_counts . '","' . $retweet_count . '", "' . $str_check . '", "' . $latitude . '", "' . $longitude . '")';
                //echo $sql;
                $sth = $dbh->query($sql);  //SQLの実行
                header('Location: twitter_home.php');
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tweet書き込み処理</title>
</head>
<body>
    <!-- 2. アカウントにログインして記事の投稿 -->
    <?php if($str_check == 1): ?>
    <p>文字数が１４０字を超えています。もう一度書き込みを行ってください。</p>
    <a href="write_message.php">メッセージ書き込みへ戻る</a>
    <?php endif; ?>

    <!-- 文字数制限で参考にした記事 -->
    <!-- phpで文字数を制限し、末尾に『』を追加するコード URL: https://spreadsheep.net/　　-->
    <!-- 閲覧日2022年1月２５日（月） -->
</body>
</html>