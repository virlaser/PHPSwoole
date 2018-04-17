<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/18
 * Time: 上午12:09
 */

$server = new swoole_websocket_server("0.0.0.0", 8812);

//$server->set([]);
$server->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/Users/vlaser/Desktop/PHPSoolw/demo/data"
    ]
);

// 监听 websocket 连接事件
$server->on('open', 'onOpen');
function onOpen($server, $request) {
    print_r($request->fd);
}

// 监听 websocket 消息事件
$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    // 向客户端推送数据
    $server->push($frame->fd, "this is server");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();