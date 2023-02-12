<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitterアカウント作成</title>
</head>
<body>
    <!-- 課題１.アカウントの作成 -->
    <!-- アカウント作成の際に必要な入力項目 -->
    <div>
        <form action="create.php" method="POST">
        〜Twitterアカウントを作成〜<br>
        <br>
         <!--入力フォーム-->
        account_name入力: <br>
        <input type="text" name="account_name" size="20"><br>
        <br>
        user_name入力: <br>
        <input type="text" name="user_name" size="20"><br>
        <br>
        login_password入力（パスワードは半角英数字で入力してください）: <br>
        <input type="text" name="login_password" size="20"><br>
        <br>
        Twitter_flag入力(自身のアカウントを作る場合は0を、フォロワーを作成する場合は１を選択してください): <br>
        <input type="text" name="twitter_flag" size="2"><br>

        <!--送信ボタン-->
        <input type="submit" value="送信">
        </form>
    </div>
</body>
</html>
