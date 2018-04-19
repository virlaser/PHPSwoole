<?php
namespace app\index\controller;

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
}
