function geoFind() {
    //位置情報取得関数
    //位置情報APIを使って投稿に位置情報機能付与を実現
    const message = document.querySelector('#message');  //ボタンを押された時にidを取得
    const latitude = document.querySelector('#latitude');
    const longitude = document.querySelector('#longitude');
  
    latitude.href = '';
    latitude.textContent = '';

    longitude.href = '';
    longitude.textContent = '';
  
    function success(position) {
        //緯度と経度を取得
      const latitude_text  = position.coords.latitude;
      const longitude_text = position.coords.longitude;
  
      latitude.href = `https://www.openstreetmap.org/#map=18/${latitude}`;
      latitude.textContent = `緯度: ${latitude_text} °`;

      longitude.href = `https://www.openstreetmap.org/#map=18/${longitude}`;
      longitude.textContent = `経度: ${longitude_text} °`;
      document.forms['form1'].elements['input1'].value = `${latitude_text}`;
      document.forms['form1'].elements['input2'].value = `${longitude_text}`;
    }
  
    function error() {
        //エラーメッセージ
      message.textContent = '位置情報を取得することができませんでした。';
    }
  
    if(!navigator.geolocation) {
      message.textContent = 'エラー ー＞Geolocationはあなたのブラウザでは対応していません。';
    } else {
        //取得待機待ち
      message.textContent = '位置情報の取得に成功しました。';
      //端末の現在地を取得してくる
      navigator.geolocation.getCurrentPosition(success, error);
    }
  
  }
  
  //ボタンクリック時に発生するイベント
  document.querySelector('#locate-now').addEventListener('click', geoFind);

  //位置情報機能参考Webサイト　　「MDN Web Docs」　位置情報API
  //URL：　https://developer.mozilla.org/ja/docs/Web/API/Geolocation_API
  //閲覧日：2022年2月１日