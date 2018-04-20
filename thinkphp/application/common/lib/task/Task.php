<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/20
 * Time: 下午10:26
 */

namespace app\common\lib\task;

class Task {

    public function sendSms($data) {
        // 发送短信的逻辑
        // 如果发送成功，把验证信息记录到 redis 里面
        return true;
    }

}