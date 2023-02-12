void getCurrentPosition(PositionCallback successCallback, optional PositionErrorCallback errorCallback, optional PositionOptions options);

function success(position) {
	console.log("緯度:" + position.coords.latitude);
	console.log("経度:" + position.coords.longitude);
}
function error() {
    document.write("位置情報の取得に失敗しました。");
}
navigator.geolocation.getCurrentPosition(success, error);