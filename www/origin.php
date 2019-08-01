<?php
/**
 * 直接请求mysql数据库
 */
//echo phpinfo();
require_once "./Scheduler.php";
require_once './utils.php';
require_once './GlobalVars.php';

$rs = [];

$start = getCurrentTime();
function getInfo(){
    //mysql直连
//    $conn = new mysqli(...GlobalVars::$mysql_link_info);
    //连接池
    $conn = new mysqli(...GlobalVars::$smproxy_link_info);

    if($conn->connect_error){
        die('连接失败'.$conn->connect_error);
    }
    $result = $conn->query('select * from t1 limit 0,1');
    $rows = [];
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $rows[] = $row;
        }
    }
    echo "<pre>";
    print_r($rows);
    $conn->close();
    return $rows;
}

function ytest1(){
        echo '任务1';
        getInfo();
}
function ytest2(){
        echo '任务2';
        getInfo();
}
ytest1();
ytest2();
$end = getCurrentTime();

echo ($end - $start).'<br>';
