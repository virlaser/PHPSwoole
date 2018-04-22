<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/20
 * Time: 下午9:51
 */

class Ws {

    CONST HOST = "0.0.0.0";
    CONST PORT = 8811;
    CONST CHART_PORT = 8812;

    public $ws = null;

    public function __construct() {
        // todo 重启时将 redis 清空

        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        $this->ws->listen(self::HOST, self::CHART_PORT, SWOOLE_SOCK_TCP);

        $this->ws->set(
            [
                'enable_static_handler' => true,
                'document_root' => "/Users/vlaser/Desktop/PHPSoolw/thinkphp/public/static",
                'worker_num' => 4,
                'task_worker_num' => 4,
            ]
        );

        $this->ws->on("workerstart", [$this, 'onWorkerStart']);
        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on("request", [$this, 'onRequest']);
        $this->ws->on("close", [$this, 'onClose']);
        $this->ws->on("task", [$this, 'onTask']);
        $this->ws->on("finish", [$this, 'onFinish']);

        $this->ws->start();
    }

    public function onOpen($ws, $request) {
        // fd to redis
        \app\common\lib\redis\Predis::getInstance()->sadd(config('redis.live_game_key'), $request->fd);
        var_dump($request->fd);
    }

    public function onMessage($ws, $frame) {
        // todo
    }

    public function onWorkerStart($server, $worker_id) {
        define('APP_PATH', __DIR__ . '/../../../application/');
        require __DIR__ . '/../../../thinkphp/start.php';
    }

    public function onRequest($request, $response) {

        // 防止请求 /favicon.ico 浪费资源，直接拦截 404
        if($request->server['request_uri'] == '/favicon.ico') {
            $response->status(404);
            $response->end();
            return '';
        }

        $_SERVER = [];
        if (isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        if (isset($request->header)) {
            foreach ($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        $_GET = [];
        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }

        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }

        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }

        $this->writeLog();

        // 可以直接在外面调用 onTask 处理耗时任务
        $_POST['http_server'] = $this->ws;

        ob_start();
        try {
            think\Container::get('app', [APP_PATH])
                ->run()
                ->send();
        } catch (\Exception $e) {
            // todo
        }

        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }

    public function onClose($ws, $fd) {
        // delete fd from redis
        \app\common\lib\redis\Predis::getInstance()->srem(config('redis.live_game_key'), $fd);
    }

    public function onTask($serv, $taskId, $workerId, $data) {
        // 此处写阿里大于发送短信的逻辑，因为没有注册所以在此不过展示

        // 分发任务机制，让不同的任务走不同的程序，工厂模式
        $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        $flag = $obj->$method($data['data'], $serv);
        return $flag;
    }

    public function onFinish($serv, $taskId, $data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-success:{$data}\n";
    }

    // 记录日志
    public  function writeLog() {
        $datas = array_merge(['data' => date("Ymd H:i:s")], $_GET, $_POST, $_SERVER);
        $logs = "";
        foreach($datas as $key => $value) {
            $logs .= $key.":".$value." ";
        }

        swoole_async_writefile(APP_PATH.'../runtime/log/'.date("Ym").'/'.date("d").'_access.log', $logs.PHP_EOL, function (){
            // todo
        }, FILE_APPEND);
    }
}

new Ws();

// lsof -i:8811 或者定时任务 crontab => swoole 定时器来监控服务

// 20台机器 agent->spark(计算)->数据库 elasticsearch 来存储分析数据