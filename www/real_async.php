<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/5/27
 * Time: 5:58 AM
 */
$start = getCurrentTime();
function getCurrentTime ()
{
    list ($msec, $sec) = explode(" ", microtime());
    return (float)$msec + (float)$sec;
}

function f1(){
    $db = new db();
    $obj = $db->async_query('select * from t1 limit 0,1');
    echo "f1 async_query \n";
    yield $obj;
    $row = $db->fetch();
    echo "f1 fetch\n";
    yield $row;
}

function f2(){
    $db = new db();
    $obj = $db->async_query('select * from t1 limit 0,1');
    echo "f2 async_query\n";
    yield $obj;
    $row = $db->fetch();
    echo "f2 fetch\n";
    yield $row;
}

$gen1 = f1();
$gen2 = f2();

$gen1->current();
$gen2->current();
$gen1->next();
$gen2->next();

$ret1 = $gen1->current();
$ret2 = $gen2->current();

var_dump($ret1);
var_dump($ret2);

class db{
    static $links;
    private $obj;

    function getConn(){
        $host = '127.0.0.1';
        $user = 'root';
        $password = '123';
        $database = 'test';
        $this->obj = new mysqli('smproxy','root','123456','test','3366');
        self::$links[spl_object_hash($this->obj)] = $this->obj;
        return self::$links[spl_object_hash($this->obj)];
    }

    function async_query($sql){
        $link = $this->getConn();
        $link->query($sql, MYSQLI_ASYNC);
        return $link;
    }

    function fetch(){
        for($i = 1; $i <= 5; $i++){
            $read = $errors = $reject = self::$links;
            $re = mysqli_poll($read, $errors, $reject, 1);
            foreach($read as $obj){
                if($this->obj === $obj){
                    $sql_result = $obj->reap_async_query();
                    $sql_result_array = $sql_result->fetch_array(MYSQLI_ASSOC);//只有一行
                    $sql_result->free();
                    return $sql_result_array;
                }
            }
        }
    }

}

$end = getCurrentTime();
$spend = $end-$start;

echo "脚本执行时间为:".$spend."\n";
