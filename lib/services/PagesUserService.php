<?php

namespace app\lib\services;

use Yii;
use yii\base\BaseObject;

class PagesUserService extends BaseObject
{
    public function writeToChat(string $message, int $authorID, int $recipientID): void
    {
        Yii::$app->getDb()->createCommand()->insert('chat', [
            'message'     => $message,
            'date_write'  => date('Y-m-d H:i:s'),
            'idauthor'    => $authorID,
            'idrecipient' => $recipientID,
        ])->execute();
    }
}
