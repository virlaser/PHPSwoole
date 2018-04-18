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

        $this->ws->set(
            [
                'worker_num' => 2,
                'task_worker_num' => 2,
            ]
        );

        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on("close", [$this, 'onClose']);
        $this->ws->on("task", [$this, 'onTask']);
        $this->ws->on("finish", [$this, 'onFinish']);

        $this->ws->start();
    }

    /*
     * 监听 ws 连接事件
     */
    public function onOpen($ws, $request) {
        var_dump($request->fd);
        if($request->fd == 1) {
            // 每两秒执行定时器
            swoole_timer_tick(2000, function ($timer_id) {
                echo "2s: timerId:{$timer_id}\n";
            });
        }
    }

    public function onMessage($ws, $frame) {
        echo "ser-push-message:{$frame->data}\n";
        // todo 10s
        $data = [
            'task' => 1,
            'fd' => $frame->fd
        ];
        // $ws->task($data);

        swoole_timer_after(5000, function () use($ws, $frame) {
            echo "5s-after\n";
            $ws->push($frame->fd, "server-time-after");
        });
        $ws->push($frame->fd, "server-push:".date("Y-m-d H:i:s"));
    }

    public function onClose($ws, $fd) {
        echo "clientid:{$fd}\n";
    }

    public function onTask($serv, $taskId, $workerId, $data) {
        print_r($data);
        // 耗时场景
        sleep(10);
        return "on task finish";
    }

    public function onFinish($serv, $taskId, $data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-success:{$data}\n";
    }
}

$obj = new Ws();