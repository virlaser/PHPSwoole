<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/19
 * Time: 下午5:04
 */

namespace app\index\controller;
use app\common\lib\ali\Sms;
use app\common\lib\Redis;
use app\common\lib\Util;
use \swoole_redis;

class Send
{
    // 发送验证码
    public function index() {

        // 解决缓存问题
        // $phoneNum = request()->get('phone_num', 0, 'intval');
        $phoneNum = intval($_GET['phone_num']);

        if(empty($phoneNum)) {
            return Util::show(config('code.error'), 'error');
        }

        $code = rand(1000, 9999);

        // 没有申请阿里平台的短信接入，因此注释掉下面的信息

//        try {
//            $response = Sms::sendSms($phoneNum, $code);
//        } catch (\Exception $e) {
//            return Util::show(config('code.error'), "阿里大于内部异常");
//        }

//      if($response->Code === "OK") {

        if(true) {
            $redis = new swoole_redis;
            $redis->connect(config('redis.host'), config('redis.port'), function (swoole_redis $redis, $result) use($phoneNum, $code) {
                $redis->set(Redis::smsKey($phoneNum), $code, function (swoole_redis $redis, $result) {
                    $redis->close();
                });
            });
            return Util::show(config('code.success'), 'success');
        } else {
            return Util::show(config('code.error'), '验证码发送失败');
        }
    }
}