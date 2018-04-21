<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/21
 * Time: 下午2:33
 */

namespace app\admin\controller;


use app\common\lib\redis\Predis;
use app\common\lib\Util;

class Live
{

    public function push(){
        // 赛况信息入库
        // 组装好数据，将数据推送给直播页面
        // 获取连接用户，遍历推送
        // 最好带一个 token ，MD5 加密
        if(empty($_GET)) {
            return Util::show(config('code.error'), 'error');
        }

        // 模拟从数据库中读取数据
        $teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
            4 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png',
            ],
        ];

        $data = [
            'type' => intval($_GET['type']),
            'title' => !empty($teams[$_GET['team_id']]['name']) ? $teams[$_GET['team_id']]['name'] : '直播员',
            'logo' => !empty($teams[$_GET['team_id']]['logo']) ? $teams[$_GET['team_id']]['logo'] : '',
            'content' => !empty($_GET['content']) ? $_GET['content'] : '',
            'images' => !empty($_GET['images']) ? $_GET['images'] : '',
        ];

//        $clients = Predis::getInstance()->sMembers(config('redis.live_game_key'));
//
//        foreach ($clients as $fd ) {
//            $_POST['http_server']->push($fd, json_encode($data));
//        }

        $taskData = [
            'method' => 'pushLive',
            'data' => $data,
        ];
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'), 'OK');
    }
}