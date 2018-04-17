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
        'document_root' => "/Users/vlaser/Desktop/PHPSoolw/demo/data"
    ]
);

$http->on('request', function($request, $response) {
    // 在终端输出传入参数
    print_r($request->get);
    // $response->cookie("singwa", "xss", time()+1800);
    // 在浏览器输出
    $response->end("<h1>HTTPServer</h1>".json_encode($request->get));
});

$http->start();

/**
 * 通过 curl 在终端访问 http://127.0.0.1:8811
 * 或者直接在浏览器访问
 */