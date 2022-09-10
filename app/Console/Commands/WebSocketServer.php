<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Ratchet\Server\IoServer;

use Ratchet\Http\HttpServer;

use Ratchet\WebSocket\WsServer;

use React\EventLoop\Factory;

use App\Http\Controllers\SocketController;

class WebSocketServer extends Command
{
   
    protected $signature = 'websocket:init';

    
    protected $description = 'Command description';

    
    public function handle()
    {
        //return 0;

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new SocketController()
                )
            ),
            8090
        );

        $server->run();
    }
}
