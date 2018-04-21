<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/21
 * Time: 下午9:06
 */

namespace app\index\controller;


use app\common\lib\Util;

class Chart {

    public function index() {

        // 登录场景 TODO

        if(empty($_POST['game_id'])) {
            return Util::show(config('code.error'), 'error');
        }
        if(empty($_POST['content'])) {
            return Util::show(config('code.error'), 'error');
        }

        $data = [
            'user' => "用户".rand(0, 2000),
            'content' => $_POST['content'],
        ];

        foreach ($_POST['http_server']->ports[1]->connections as $fd) {
            $_POST['http_server']->push($fd, json_encode($data));
        }

        return Util::show(config('code.success'), 'OK', $data);
    }

}