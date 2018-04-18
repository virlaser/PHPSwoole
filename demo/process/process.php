<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/18
 * Time: 下午5:42
 */

$process = new swoole_process(function (swoole_process $pro) {
    // todo
    // 为 true 的话会把输出放入管道
    // echo "test";
    $pro->exec("/Users/vlaser/Desktop/PHPSoolw/php/bin/php", [__DIR__.'/../server/http_server.php']);
}, true);

$pid = $process->start();
echo $pid.PHP_EOL;

swoole_process::wait();