<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/18
 * Time: 下午1:33
 */

// 可将 swoole_async_readfile 换为 Swoole\Async::readfile
$result = swoole_async_readfile(__DIR__."/test.txt", function ($filename, $fileContent) {
    echo "filename:".$filename.PHP_EOL;
    echo "content:".$fileContent.PHP_EOL;
});

// 观看打印顺序
var_dump($result);
echo "start".PHP_EOL;