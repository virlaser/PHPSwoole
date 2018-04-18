<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/18
 * Time: 下午1:55
 */

/*
  create table test (
  id int unsigned auto_increment,
  username varchar(30) not null ,
  create_time DATE,
  primary key (id)
)ENGINE = InnoDB default charset = utf8;
 */

//观察打印出出的信息，感受异步运行
class AysMysql {

    public $dbConfig = [];
    public  function __construct() {
        $this->dbSource = new Swoole\Mysql;

        $this->dbConfig = [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => '',
            'database' => 'swoole',
            'charset' => 'utf8',
        ];
    }

    public function update() {

    }

    public function add() {

    }

    /*
     * mysql 执行逻辑
     */
    public function execute($id, $username) {
        // connect
        $this->dbSource->connect($this->dbConfig, function ($db, $resule) {
            echo "mysql-connect".PHP_EOL;
            if($resule === false) {
                var_dump($db->connect_error);
                // tode
            }

            $sql = "select * from test where id=1";
            // query
            $db->query($sql, function ($db, $result) {
                // select => result 返回的是查询的结果集
                // add update delete
                if($result === false) {
                    //todo
                }elseif($result === true) {
                    //todo
                }else {
                    print_r($result);
                }
                $db->close();
            });
        });
        return true;
    }
}

$obj = new AysMysql();
$flag = $obj->execute(1, 'test');
var_dump($flag).PHP_EOL;
echo "start".PHP_EOL;