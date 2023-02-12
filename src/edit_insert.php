<?php
session_start();

//接続用の関数の呼び出し
require_once(__DIR__ . '/functions.php');

//書き込みがあるかどうかの確認
if (!(isset($_POST['intro']))) {
    //header('Location: profile.php');
    echo '失敗';
}

//自己紹介文
$intro = htmlspecialchars($_POST['intro'], ENT_QUOTES);

$flag1 = 0;

//ファイル名の取り出し
$icon_name = $_FILES['icon_name']['name'];
if ($icon_name == '') {
    $flag1 = 1;
} else {
    $flag1 = 0;
}

$file_type1 = $_FILES['icon_name']['type'];
//一時ファイル名の取り出し
$temp_name1 = $_FILES['icon_name']['tmp_name'];

//保存先のディレクトリ
$dir1 = 'upload/';

//保存先のファイル名
$icon_name = $dir1 . $icon_name;

//JPEG形式のファイルをアップロードする
//JPEG形式のファイルをアップロードする
if (($file_type1 == 'image/jpeg') ||
($file_type1 == 'image/pjpeg') ||
($file_type1 == 'image/png')) {
    //アップロード（移動）
    $result = move_uploaded_file($temp_name1, $icon_name);
    
    if ($result) {
        //アップロードの成功
        echo '->アイコン画像のアップロードに成功しました。' . "<br>";
    } else {                                                                                                                                                          
        //アップロードの失敗
        echo 'アップロードの失敗' . "<br>";   
    }
} else {
    //JPEG形式以外のファイルの対応
    echo 'JPEG/PNG形式の画像をアップロードしてください' . "<br>";
}

//ファイル情報を取り出す
$file_info1 = pathinfo ($icon_name);

//ファイル名
$icon_name = $dir1 . $file_info1['basename'];
//echo $file_name;
//ここまでアイコン画像アップロード処理

//ここからヘッダー画像アップロード処理
$flag2 = 0;
//ファイル名の取り出し
$header_name = $_FILES['header_name']['name'];
if ($header_name == '') {
    $flag2 = 1;
} else {
    $flag2 = 0;
}

$file_type2 = $_FILES['header_name']['type'];
//一時ファイル名の取り出し
$temp_name2 = $_FILES['header_name']['tmp_name'];

//保存先のディレクトリ
$dir = 'upload/';

//保存先のファイル名
$header_name = $dir . $header_name;

//JPEG形式のファイルをアップロードする
//JPEG形式のファイルをアップロードする
if (($file_type2 == 'image/jpeg') ||
($file_type2 == 'image/pjpeg') ||
($file_type2 == 'image/png')) {
    //アップロード（移動）
    $result = move_uploaded_file($temp_name2, $header_name);
    
    if ($result) {
        //アップロードの成功
        echo '->ヘッダー画像のアップロードに成功しました。' . "<br>";
    } else {                                                                                                                                                          
        //アップロードの失敗
        echo 'アップロードの失敗' . "<br>";   
    }
} else {
    //JPEG形式以外のファイルの対応
    echo 'JPEG/PNG形式の画像をアップロードしてください' . "<br>";
}

//ファイル情報を取り出す
$file_info2 = pathinfo ($header_name);

//ファイル名
$header_name = $dir . $file_info2['basename'];
//echo $file_name;

//DBへの接続
$dbh = connectDB();

if ($dbh) {  //接続に成功した場合
    //データベースへの問い合わせSQL文（文字列）
    $sql = 'SELECT `profile_id` FROM `profile_tb`';
    //echo $sql;
    $sth = $dbh->query($sql);  //SQLの実行
}

while($row = $sth->fetch()) {   
    $profile_id = $row['profile_id'];
}

if (isset($profile_id)) {
    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'UPDATE `profile_tb` SET `profile_id` = "' . $_SESSION['user_id'] . '", 
        `account_name` = "' . $_SESSION['account_name'] . '",
        `user_name` = "' . $_SESSION['user'] . '",
        `icon_img` = "' . $icon_name . '",
        `header_img` = "' . $header_name . '",
        `intro` = "' . $intro . '" ';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行
    }
}else {
    if ($dbh) {  //接続に成功した場合
        //データベースへの問い合わせSQL文（文字列）
        $sql = 'INSERT INTO `profile_tb`(`profile_id`,`account_name`,`user_name`, `icon_img`, `header_img`, `intro`) VALUES ("' . $_SESSION['user_id'] . '", "' . $_SESSION['account_name'] . '","' . 
        $_SESSION['user'] . '","' . $icon_name . '", "' . $header_name . '", "' . $intro . '")';
        //echo $sql;
        $sth = $dbh->query($sql);  //SQLの実行
    }
}
    
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール編集処理</title>
</head>

<body>
<?php
    if ($sth == FALSE) {
        echo '■入力に失敗しています。';
    }else{
        echo 'プロフィールの更新が完了しました。' . '<br>';
        echo '<a href="profile.php">プロフィールへ</a><br>';
    }
    ?>
</body>
</html>