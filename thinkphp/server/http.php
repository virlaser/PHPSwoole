<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/20
 * Time: 下午9:51
 */

class http {

    CONST HOST = "0.0.0.0";
    CONST PORT = 8811;

    public $http = null;
    public function __construct(){
        $this->http = new swoole_http_server(self::HOST, self::PORT);

        $this->http->set(
            [
                'enable_static_handler' => true,
                'document_root' => "/Users/vlaser/Desktop/PHPSoolw/thinkphp/public/static",
                'worker_num' => 4,
                'task_worker_num' => 4,
            ]
        );

        $this->http->on("workerstart", [$this, 'onWorkerStart']);
        $this->http->on("request", [$this, 'onRequest']);
        $this->http->on("close", [$this, 'onClose']);
        $this->http->on("task", [$this, 'onTask']);
        $this->http->on("finish", [$this, 'onFinish']);

        $this->http->start();
    }

    public function onWorkerStart($server, $worker_id) {
        define('APP_PATH', __DIR__.'/../application/');
        // require __DIR__.'/../thinkphp/base.php';
        require __DIR__.'/../thinkphp/start.php';
    }

    public function onRequest($request, $response) {

        $_SERVER = [];
        if(isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        if(isset($request->header)) {
            foreach ($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        $_GET = [];
        if(isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }

        $_POST = [];
        if(isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }

        // 可以直接在外面调用 onTask 处理耗时任务
        $_POST['http_server'] = $this->http;

        ob_start();
        try {
            think\Container::get('app', [APP_PATH])
                ->run()
                ->send();
        }catch(\Exception $e) {
            // todo
        }

        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }

    public function onClose($ws, $fd) {
        echo "clientid:{$fd}\n";
    }

    public function onTask($serv, $taskId, $workerId, $data) {
        // 此处写阿里大于发送短信的逻辑，因为没有注册所以在此不过展示

        // 分发任务机制，让不同的任务走不同的程序，工厂模式
        $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        $flag = $obj->$method($data['data']);
        return $flag;
    }

    public function onFinish($serv, $taskId, $data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-success:{$data}\n";
    }
}

new Http();