<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/20
 * Time: 下午8:56
 */

namespace app\index\controller;


use app\common\lib\Redis;
use app\common\lib\redis\Predis;
use app\common\lib\Util;

class Login {

    public function index() {
        $phoneNum = intval($_GET['phoneNum']);
        $code = intval($_GET['authCode']);
        if (empty($phoneNum) || empty($code)) {
            return Util::show(config('code.error'), 'phone or code is error');
        }


        try {
            $redisCode = Predis::getInstance()->get(Redis::smsKey($phoneNum));
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
        if($redisCode == $code) {

            $data = [
                'user' => $phoneNum,
                'srcKey' => md5(Redis::userKey($phoneNum)),
                'time' => time(),
                'isLogin' => true,
            ];

            Predis::getInstance()->set(Redis::userKey($phoneNum), $data);

            return Util::show(config('code.success'), 'ok', $data);
        } else {
            return Util::show(config('code.error'), 'login error');
        }
    }

}