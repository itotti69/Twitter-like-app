    <?php
    //セッション生成
    session_start();

    //接続用関数の呼び出し
    require_once(__DIR__.'/functions.php');
    ?>

    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>位置情報を基にした記事の閲覧１</title>
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="setup.css">
    </head>
    <body>
    <p>位置情報を基にした記事の閲覧ページに移行します。</p>
    <form action="location_show2.php" method="POST" name="form1">
    <input type="submit" value="閲覧する"><br>
        <input type="hidden" name="input1" id="latitude">
        <input type="hidden" name="input2" id="longitude">
    </form>
    <p>先にこちらをクリックして現在地を取得してください。↓</p><br>
    

    <button id="locate-now">位置情報取得</button><br/>

    <p id="message"></p>
    <p>「位置情報の取得に成功しましたというメッセージが出ましたら、「閲覧するボタンを押してください」</p><br>

    <div class="back">
    <a href="menu_message.php" class="linkbutton"><i class="fas fa-arrow-alt-circle-left"></i></a>
    </div>

    <script type="text/javascript" src="js/location.js"></script>
    </body>
    </html>