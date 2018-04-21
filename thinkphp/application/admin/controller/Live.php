<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/21
 * Time: 下午2:33
 */

namespace app\admin\controller;


use app\common\lib\redis\Predis;

class Live
{

    public function push(){
        // 赛况信息入库
        // 组装好数据，将数据推送给直播页面
        // 获取连接用户，遍历推送
        $clients = Predis::getInstance()->sMembers(config('redis.live_game_key'));

        foreach ($clients as $fd ) {
            $_POST['http_server']->push($fd, 'hello world');
        }
    }
}