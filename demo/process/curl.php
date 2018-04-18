<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/18
 * Time: 下午6:00
 */

echo "process-start-time:".date("Ymd H:i:s").PHP_EOL;
$workers = [];

$urls = [
    'http://baidu.com',
    'http://sina.com.cn',
    'http://haidu.com?search=vlaser',
    'http://baidu.com?search=vlaser2',
    'http://baidu.com?search=imooc',
    'http://qq.com',
];

for ($i = 0; $i < 6; $i++) {
    // 开 6 个子进程
    $process = new swoole_process(function (swoole_process $worker) use ($i, $urls) {
        // todo
        $content = curlData($urls[$i]);
        // 将内容输出到管道
        // echo $content . PHP_EOL;
        $worker->write($content.PHP_EOL);
    }, true);
    $pid = $process->start();
    $workers[$pid] = $process;
}

foreach ($workers as $process) {
    // 读取管道里面的内容
    echo $process->read();
}

// 模拟请求
function curlData($url)
{
    sleep(1);
    return $url . "success" . PHP_EOL;
}

echo "process-end-time:".date("Ymd H:i:s").PHP_EOL;