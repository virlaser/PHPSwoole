<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/17
 * Time: 下午11:40
 */
$http = new swoole_http_server("0.0.0.0", 8811);

//直接获取静态内容，然后返回，不走下面的逻辑
//可直接访问 http://127.0.0.1:8811/index.html 得到返回的网页
$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/Users/vlaser/Desktop/PHPSoolw/thinkphp/public/static",
        'worker_num' => 5,
    ]
);

$http->on('WorkerStart', function (swoole_server $server, $worker_id) {
    // 定义应用目录
    define('APP_PATH', __DIR__.'/../application/');
    // 加载框架文件
    require __DIR__.'/../thinkphp/base.php';
});

$http->on('request', function($request, $response) use($http) {

    // 转换为 thinkphp 需要的变量
    // 直接赋为空，避免缓存问题
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

    // 在 swoole 中超级全局变量不会被释放
//    if(!empty($_GET)) {
//        unset($_GET);
//    }
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

    ob_start();
    try {
        // 执行应用并响应
        think\Container::get('app', [APP_PATH])
            ->run()
            ->send();
    }catch(\Exception $e) {
        // todo
    }

    $res = ob_get_contents();
    ob_end_clean();
    $response->end($res);

    // 清除缓存
    // http://127.0.0.1:8811/?s=index/index/index
    // todo 终端会报错
    // $http->close();
});

$http->start();

/**
 * 通过 curl 在终端访问 http://127.0.0.1:8811
 * 或者直接在浏览器访问
 */