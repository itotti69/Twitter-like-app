

$(function() {
    //検索用のボックスが更新されるたびに呼ばれる
    $('#search_users').on('input', function(){
        console.log("search_users呼ばれた");
        $.ajax({
            //送信方法
            type: "POST",
            //送信先ファイル名
            url: "search_manager.php",
            //受け取りデータの種類
            datatype: "json",
            //送信データ
            data: {
                //#titleと#messageのvalueをセット
                "search_users" : $('#search_users').val()
            }
        })
        .then(
        //成功時の処理
        function(data) {
            //ヘッダ以外削除
            $("#all_show_result").find("tr:gt(0)").remove();
            //一覧に追加したレコードの追記
            $.each(data, function(key, value) {
            //#all_show_result内にappendで追記
            $("#all_show_result").prepend(
                "<tr><td>" +
                value.user_id +
                "</td><td>" +
                value.account_name +
                "</td><td>" +
                value.user_name +
                "</td>"
            )
          });
          console.log("通信成功（search_users）");
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