<?php

//echo phpinfo();
require_once "./Scheduler.php";
require_once './GlobalVars.php';

$rs = [];
function getCurrentTime ()
{
    list ($msec, $sec) = explode(" ", microtime());
    return (float)$msec + (float)$sec;
}
$start = getCurrentTime();
function getInfo(){
//    $conn = new mysqli(...GlobalVars::$mysql_link_info);
    $conn = new mysqli(...GlobalVars::$smproxy_link_info);

//    $conn = new mysqli('47.107.65.73','bi_vinston_net','2e8TCPKCSrai5AfS','bi_vinston_net','3306');
    if($conn->connect_error){
        die('连接失败'.$conn->connect_error);
    }
//echo '成功';
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
//    echo json_encode($rows);
}
function ytest(){
    yield getInfo();
    yield getInfo();
    yield getInfo();
    yield getInfo();
    yield getInfo();
    yield getInfo();
    yield getInfo();
    yield getInfo();
}
function ytest1(){
//    for ($i=0;$i<5;$i++){
        echo '任务1';

        getInfo();
        yield;
//    }
}
function ytest2(){
//    for ($i=0;$i<5;$i++){
        echo '任务2';
        getInfo();
        yield;
//    }
}function ytest3(){
    $rs[] = getInfo();
    yield;
}
function ytest4(){
    $rs[] = getInfo();
    yield;
}
function ytest5(){
    $rs[] = getInfo();
    yield;
}function ytest6(){
    $rs[] = getInfo();
    yield;
}function ytest7(){
    $rs[] = getInfo();
    yield;
}function ytest8(){
    $rs[] = getInfo();
    yield;
}
//for($i=0;$i<7;$i++){
////    $rs[] = getInfo();
//
//ytest1();
//ytest2();
//ytest3();
//ytest4();
//ytest5();
//ytest6();
//ytest7();
//ytest8();
////    foreach (ytest() as $v){
////        $rs[] = $v;
////    }
//    foreach (ytest1() as $v){
//        $rs[] = $v;
//    }
//}
//sleep(5);

// getInfo();
// getInfo();
//echo json_encode($rs);
$scheduler = new Scheduler();

$scheduler->newTask(ytest1());
$scheduler->newTask(ytest2());

$scheduler->run();
//ytest1();
//ytest2();
$end = getCurrentTime();

echo ($end - $start).'<br>';
//echo 2.55-3.55;
