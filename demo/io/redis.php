<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/18
 * Time: 下午3:43
 */

$redisClient = new swoole_redis;
$redisClient->connect('127.0.0.1', 6379, function (swoole_redis $redisClient, $result) {
    echo "connect".PHP_EOL;
    var_dump($result);

    // 同步 redis (new Redis())->set('key',2);
    $redisClient->set('vlaser', time(), function (swoole_redis $redisClient, $result) {
        var_dump($result);
        $redisClient->close();
    });

//    $redisClient->get('vlaser', function (swoole_redis $redisClient, $result) {
//        var_dump($result);
//        $redisClient->close();
//    });

//    $redisClient->keys('*', function (swoole_redis $redisClient, $result) {
//        var_dump($result);
//        $redisClient->close();
//    });
});

echo "start".PHP_EOL;