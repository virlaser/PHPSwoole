<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/20
 * Time: 下午10:26
 */

namespace app\common\lib\task;

use app\common\lib\redis\Predis;

class Task {

    public function sendSms($data, $serv) {
        // 发送短信的逻辑
        // 如果发送成功，把验证信息记录到 redis 里面
        return true;
    }

    // 通过 task 机制发送赛况数据
    public function pushLive($data, $serv) {
        $clients = Predis::getInstance()->sMembers(config('redis.live_game_key'));

        // TODO 聊天室和直播的端口数据有冲突
        foreach ($clients as $client) {
            $serv->push($client, json_encode($data));
        }
    }

}