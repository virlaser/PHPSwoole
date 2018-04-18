<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/18
 * Time: 下午1:41
 */

$content = date("Ynd H:i:s").PHP_EOL;
// FILE_APPEND 以追加的形式写，否则就覆盖
swoole_async_writefile(__DIR__."/test.log", $content, function ($filename) {
    // todo
    echo "success".PHP_EOL;
}, FILE_APPEND);

echo "start".PHP_EOL;