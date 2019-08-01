<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/5/23
 * Time: 6:40 AM
 */
//
//function gen() {
//    $ret = (yield 'yield1');
//    var_dump($ret);
//    $ret = (yield 'yield2');
//    var_dump($ret);
//}
//
//$gen = gen();
//var_dump($gen->current());    // string(6) "yield1"
//var_dump($gen->send('ret1')); // string(4) "ret1"   (the first var_dump in gen)
//// string(6) "yield2" (the var_dump of the ->send() return value)
//var_dump($gen->send('ret2')); // string(4) "ret2"   (again from within gen)
//// NULL
require_once "./Scheduler.php";
function task1() {
    for ($i = 1; $i <= 10; ++$i) {
        echo "This is task 1 iteration $i.\n";
        yield;
    }
}

function task2() {
    for ($i = 1; $i <= 5; ++$i) {
        echo "This is task 2 iteration $i.\n";
        yield;
    }
}

$scheduler = new Scheduler();

$scheduler->newTask(task1());
$scheduler->newTask(task2());

$scheduler->run();