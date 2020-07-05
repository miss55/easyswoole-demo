<?php
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 8500,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER,
        //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time' => 3,
        ],
        'TASK' => [
            'workerNum' => 4,
            'maxRunningNum' => 128,
            'timeout' => 15,
        ],
    ],
    'TEMP_DIR' => "/tmp/easy/",
    'LOG_DIR' => null,
    "REDIS" => [
        'host' => 'redis',
        'port' => 6379,
        'auth' => '',
        'db' => null,
        'serialize' => 0,
    ],
    "MYSQL" => [
        'host' => 'mysql',
        'user' => 'root',
        'password' => '123456',
        'database' => 'easy',
        'port' => 3306,
    ],
    "KAFKA" => [
        "brokers" => [
            "kafka1:9092,kafka2:9093"
        ],
        "version" => "2.5.0",
        "refreshIntervalMs" => 1000,
        "consume_mode" => 1, //先提交，再消费
        "pool" => [
            "queueName" => "easy_kafka",
            "daemon" => false,
            "workerNum" => 1,
        ],
    ],
];
