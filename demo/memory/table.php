<?php
/**
 * Created by PhpStorm.
 * User: vlaser
 * Date: 2018/4/18
 * Time: 下午11:03
 */

// 创建内存表
$table = new swoole_table(1024);

// 内存表增加一列
$table->column('id', $table::TYPE_INT, 4);
$table->column('name', $table::TYPE_STRING, 64);
$table->column('age', $table::TYPE_INT, 3);
$table->create();

$table->set('vlaser_demo', ['id' => 1, 'name' => 'vlaser', 'age' => 19]);

// 另外一种方案
$table['vlaser_demo2'] = [
    'id' => 2,
    'name' => 'vlaser2',
    'age' => 31,
];

print_r($table->get('vlaser_demo'));

// 原子增加
$table->incr('vlaser_demo2', 'age', 2);
print_r($table['vlaser_demo2']);

// del , decr 删除，原子减操作等，可参考官方文档