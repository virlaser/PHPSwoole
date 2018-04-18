<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/18
 * Time: 下午11:18
 */

$http = new swoole_http_server('0.0.0.0', 8801);

$http->on('request', function ($request, $response) {
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 6379);
    $value = $redis->get($request->get['a']);

    // mysql code block
    // 所需时间计算 time = max(redis, mysql)

    $response->header("Content-Type", "text/plain");
    $response->end($value);
});

$http->start();