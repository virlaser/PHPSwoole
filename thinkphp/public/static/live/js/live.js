var wsUrl = "ws://127.0.0.1:8811";
var websocket = new WebSocket(wsUrl);

//实例对象的 onopen 属性
websocket.onopen = function (evt) {
    websocket.send("hello world");
    console.log("connected-swoole-success");
}

//实例化 onmessage
websocket.onmessage = function (evt) {
    console.log("ws-server-return-date:" + evt.data);
}

//实例化 onclose
websocket.onclose = function (evt) {
    console.log("close")
}

//onerror
websocket.onerror = function (evt, e) {
    console.log("error:" + evt.data);
}