<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編集ページ</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
</head>
<body>
    <!-- 24. プロフィールの編集 -->
    <div class="title">
        <h1>プロフィール編集</h1>
    </div>
    <a href="profile.php" class="linkbutton"><i class="fas fa-arrow-alt-circle-left"></i></a>

    <form action="edit_insert.php" method="POST" enctype="multipart/form-data">
    <!-- アイコン画像設定：<br> -->
    アイコン設定：
    <input type="file" name="icon_name" required><br>
    <!-- ヘッダー画像設定：<br> -->
    ヘッダー画像設定：
    <input type="file" name="header_name" required><br>
    <!-- 自己紹介メッセージ入力<br> -->
    自己紹介メッセージ設定：
    <textarea name="intro" cols="40" rows="5" placeholder="自己紹介文"></textarea>
    <input type="submit" value="保存">
    </form>

</body>
</html>