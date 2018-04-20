<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/20
 * Time: 上午8:40
 */

namespace app\common\lib;

class Redis {
    // 短信前缀
    public static $pre = "sms_";
    // 用户前缀
    public static $userpre = "user_";

    // 存储验证码 redis key 值
    public static function smsKey($phone) {
        return self::$pre.$phone;
    }

    public static function userKey($phone) {
        return self::$userpre.$phone;
    }
}