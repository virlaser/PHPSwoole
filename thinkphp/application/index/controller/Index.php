<?php
namespace app\index\controller;
use app\common\lib\ali\Sms;

class Index
{
    public function index()
    {
        print_r($_GET);
        echo 'vlaser-hello';
    }

    public function vlaser() {
        echo time();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function sms() {
        try {
            Sms::sendSms(15871399329, 12345);
        } catch (\Exception $e) {
            // todo
        }
    }
}
