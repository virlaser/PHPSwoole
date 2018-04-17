<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/18
 * Time: 上午12:38
 */

class Ws {

    CONST HOST = "0.0.0.0";
    CONST PORT = 8812;

    public $ws = null;
    public function __construct(){
        $this->ws = new swoole_websocket_server("0.0.0.0", 8812);

        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on("close", [$this, 'onClose']);

        $this->ws->start();
    }

    /*
     * 监听 ws 连接事件
     */
    public function onOpen($ws, $request) {
        var_dump($request->fd);
    }

    public function onMessage($ws, $frame) {
        echo "ser-push-message:{$frame->data}\n";
        $ws->push($frame->fd, "server-push:".date("Y-m-d H:i:s"));
    }

    public function onClose($ws, $fd) {
        echo "clientid:{$fd}\n";
    }
}

$obj = new Ws();