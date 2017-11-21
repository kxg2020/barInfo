<?php
use \Workerman\Worker;
use \Workerman\Lib\Timer;
require_once'../workerman/Autoloader.php';

$ws_worker = new Worker('websocket://0.0.0.0:8080');

$ws_worker->count = 2;
// 连接建立时给对应连接设置定时器
$ws_worker->onConnect = function($connection)
{
    // 每10秒执行一次
    $time_interval = 3;

    // redis
    $redis = new Redis();

    $redis->pconnect('127.0.0.1');

    $connect_time = time();
    // 给connection对象临时添加一个timer_id属性保存定时器id
    $connection->timer_id = Timer::add($time_interval, function()use($connection, $connect_time,$redis){

        // 查询用户
        $user = $redis->hLen('users');

        // 查询客服
        $staff = $redis->hLen('staffs');


        $connection->send(json_encode(['user'=>$user,'staff'=>$staff],JSON_UNESCAPED_UNICODE));
    });
};
// 连接关闭时，删除对应连接的定时器
$ws_worker->onClose = function($connection)
{
    // 删除定时器
    Timer::del($connection->timer_id);
};

// 运行worker
Worker::runAll();