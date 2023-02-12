$(function() {
    //検索用のボックスが更新されるたびに呼ばれる
    $('#search_message').on('input', function(){
        console.log("search_message呼ばれた");
        $.ajax({
            //送信方法
            type: "POST",
            //送信先ファイル名
            url: "search_message.php",
            //受け取りデータの種類
            datatype: "json",
            //送信データ
            data: {
                //#titleと#messageのvalueをセット
                "search_message" : $('#search_message').val()
            }
        })
        .then(
        //成功時の処理
        function(data) {
            //ヘッダ以外削除
            $("#all_show_tweet").find("tr:gt(0)").remove();
            //一覧に追加したレコードの追記
            $.each(data, function(key, value) {
            //#all_show_result内にappendで追記
            $("#all_show_tweet").prepend(
                "<tr><td>" +
                value.account_name +
                "</td><td>" +
                value.user_name +
                "</td><td>" +
                value.entry_date +
                "</td><td>" +
                value.message +
                "</td></tr>"
            )
          });
          console.log("通信成功（search_message）");
        },
        //エラーのときの処理
        function(XMLHttpRequest, textStatus, errorThrown) {
            console.log('通信失敗!!!');
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        }
        );
    })
}); 