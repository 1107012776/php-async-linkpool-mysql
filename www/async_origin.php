<?php
/**
 * 直接请求mysql数据库
 */
//echo phpinfo();
require_once "./Scheduler.php";
require_once './utils.php';
require_once './GlobalVars.php';

$start = getCurrentTime();

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

echo "<pre>";
print_r($ret1);
echo "<pre>";
print_r($ret2);


class db{
    static $links;
    private $obj;

    function getConn(){
        $host = '127.0.0.1';
        $user = 'root';
        $password = '123';
        $database = 'test';
//        $this->obj = new mysqli(...GlobalVars::$mysql_link_info);
        $this->obj = new mysqli(...GlobalVars::$smproxy_link_info);


        if($this->obj->connect_error){
            die('连接失败'.$this->obj->connect_error);
        }
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
            //  这里我还需要去详细了解下  有机会再补充吧
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
