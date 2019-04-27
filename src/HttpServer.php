<?php
/**
 * Created by PhpStorm.
 * User: dunkebiao
 * Date: 2019-04-27
 * Time: 11:14
 */

namespace Swoole\Laravel;

use Illuminate\Http\Request;
use Swoole\Http\Server;

class HttpServer extends Server
{
    protected $app;

    public function __construct($host, $port = null, $mode = null, $sock_type = null)
    {
        parent::__construct($host, $port, $mode, $sock_type);
        $this->bindEvent();
    }

    public function bindEvent()
    {
        $this->on('Start', [$this, 'onStart']);
        $this->on('Shutdown', [$this, 'onShutdown']);
        $this->on('ManagerStart', [$this, 'onManagerStart']);
        $this->on('ManagerStop', [$this, 'onManagerStop']);
        $this->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->on('WorkerStop', [$this, 'onWorkerStop']);
        $this->on('WorkerError', [$this, 'onWorkerError']);
        $this->on('PipeMessage', [$this, 'onPipeMessage']);
        $this->on('Receive', [$this, 'onReceive']);
        $this->on('Request', [$this, 'onRequest']);
    }

    public function setLaravel($app)
    {
        $this->app = $app;
    }

    public function onStart(Server $server)
    {
        echo 'start' . "\n";
    }

    public function onShutdown(\swoole_server $server)
    {
        echo 'Shutdown' . "\n";
    }

    public function onManagerStart(\swoole_server $serv)
    {
        echo 'ManagerStart' . "\n";
    }

    public function onManagerStop(\swoole_server $serv)
    {
        echo 'ManagerStop' . "\n";
    }

    public function onWorkerStart(\swoole_server $server, int $worker_id)
    {
        echo 'WorkerStart' . "\n";
    }

    public function onWorkerStop(\swoole_server $server, int $worker_id)
    {
        echo 'WorkerStop' . "\n";
    }

    public function onWorkerError(\swoole_server $serv, int $worker_id, int $worker_pid, int $exit_code, int $signal)
    {
        echo 'WorkerError' . "\n";
    }

    public function onPipeMessage(\swoole_server $server, int $src_worker_id, mixed $message)
    {
        echo 'PipeMessage' . "\n";
    }

    public function onReceive(\swoole_server $server, int $fd, int $reactor_id, string $data)
    {
        echo 'Receive' . "\n";
    }

    public function onRequest(\swoole_http_request $swooleRequest, \swoole_http_response $swooleResponse)
    {
        $_COOKIE = $swooleRequest->cookie ?? [];
        $_SERVER = $swooleRequest->server ?? [];
        $_GET = $swooleRequest->get ?? [];
        $_POST = $swooleRequest->post ?? [];

        $response = $this->app->handle(
            $request = Request::capture()
        );

        $this->app->terminate($request, $response);

        $swooleResponse->end($response->getContent());
    }
}