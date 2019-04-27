<?php
/**
 * Created by PhpStorm.
 * User: dunkebiao
 * Date: 2019-04-27
 * Time: 11:15
 */


error_reporting(E_ERROR);

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$config = [
    'worker_num' => 4,
    'log_file' => 'http.log',
    'upload_tmp_dir' => '/tmp/',
    'http_parse_post' => true,
    'document_root' => __DIR__ . '../public',
    'enable_static_handler' => true,
    'http_compression' => true,
];

$server = new \Swoole\Laravel\HttpServer(
    '127.0.0.1',
    '8080',
    SWOOLE_PROCESS,
    SWOOLE_SOCK_TCP
);
$server->set($config);
$server->setLaravel($kernel);
$server->start();