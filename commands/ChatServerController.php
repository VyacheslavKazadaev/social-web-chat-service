<?php
namespace app\commands;
use app\lib\daemons\ChatServer;
use consik\yii2websocket\WebSocketServer;
use yii\console\Controller;

class ChatServerController extends Controller
{
    public function actionStart()
    {
        $server = new ChatServer();
        $server->port = 9859; //This port must be busy by WebServer and we handle an error

        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN_ERROR, function($e) use($server) {
            echo "Error opening port " . $server->port . "\n";

            $server->port += 1; //Try next port to open
            $server->start();
        });

        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN, function($e) use($server) {
            echo "Server started at port " . $server->port;
        });

        $server->on(WebSocketServer::EVENT_CLIENT_MESSAGE, function($e) use($server) {
            echo "\nEvent client message " . var_export($e->message, true);
        });

        $server->start();
    }
}
