<?php
    //セッションスタート
    session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン成功画面</title>
</head>
<body>
    <?php
    //ログイン確認
    if ((isset($_SESSION['login']) && $_SESSION['login'] == 'OK')) {
        //ログイン成功
        echo '■ログイン中です。' . '<br />';
        echo '<br />';
        echo '接続ユーザ:'  . $_SESSION['name'];
    } else {
        //ログイン失敗
        echo '■ログインしていません。';
    }
    ?>    
</body>
</html>