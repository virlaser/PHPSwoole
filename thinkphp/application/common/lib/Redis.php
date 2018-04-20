<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/20
 * Time: 上午8:40
 */

namespace app\common\lib;

class Redis {

    public static $pre = "sms_";

    // 存储验证码 redis key 值
    public static function smsKey($phone) {
        return self::$pre.$phone;
    }
}