<?php
namespace app\lib\daemons;
use app\lib\services\PagesUserService;
use consik\yii2websocket\events\WSClientEvent;
use consik\yii2websocket\WebSocketServer;
use Ratchet\ConnectionInterface;
use yii\web\View;

class ChatServer extends WebSocketServer
{
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_CLIENT_CONNECTED, function(WSClientEvent $e) {
            $e->client->author = null;
            $e->client->recipient = null;
        });
    }

    protected function getCommand(ConnectionInterface $from, $msg)
    {
        $request = json_decode($msg, true);
        return !empty($request['action']) ? $request['action'] : parent::getCommand($from, $msg);
    }

    public function commandChat(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);

        if (!empty($request['message']) && $message = trim($request['message']) ) {
            $author = $request['author'];
            $recipient = $request['recipient'];
            (new PagesUserService())->writeToChat($message, (int)$author['id'], (int)$recipient['id']);

            $messages = [['message' => $message, 'idauthor' => $author['id']]];

            $view = new View();
            $handledClients = 0;
            $messageChat = '';
            foreach ($this->clients as $chatClient) {
                if ($handledClients == 2) {
                    break;
                }

                if ($chatClient == $client) {
                    $messageChat = $view->render('//chat/_chat_message', compact('messages', 'author', 'recipient'));
                } elseif ($chatClient->author['id'] == $recipient['id']) {
                    $messageChat = $view->render('//chat/_chat_message', [
                        'messages'  => $messages,
                        'author'    => $recipient,
                        'recipient' => $author
                    ]);
                }

                if ($chatClient == $client || $chatClient->author['id'] == $recipient['id']) {
                    ++$handledClients;
                    $chatClient->send(json_encode([
                        'type' => 'chat',
                        'message' => $messageChat
                    ]));
                }
            }
        }
    }

    public function commandPing(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);
        $result = ['message' => 'pong'];

        if (!empty($request['author']) && !empty($request['recipient'])) {
            $client->author = $request['author'];
            $client->recipient = $request['recipient'];
        }

        $client->send( json_encode($result) );
    }
}
