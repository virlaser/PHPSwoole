<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/22
 * Time: 下午2:18
 */

class Server {
    const PORT = 8811;

    public function prot() {
        $shell = "lsof -i:".self::PORT." | grep LISTEN";

        $result = shell_exec($shell);

        if(empty($result)) {
            // 报警
            // todo
            echo $result;
            echo date("Ymd H:i:s")."error"."--".PHP_EOL;
        } else {
            echo date("Ymd H:i:s")."success"."--".PHP_EOL;
        }
    }
}

// nohup 命令，不间断的把脚本放在后台执行
swoole_timer_tick(2000, function($timer_id) {
    (new Server())->prot();
});

