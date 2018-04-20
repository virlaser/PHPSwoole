<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/20
 * Time: 上午8:10
 */

namespace app\common\lib;

class Util
{

    public static function show($status, $message = '', $data = []) {
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        echo json_encode($result);
    }
}