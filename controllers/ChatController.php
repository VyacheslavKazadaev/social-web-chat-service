<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class ChatController extends Controller
{
    /**
     * List of allowed domains.
     * Note: Restriction works only for AJAX (using CORS, is not secure).
     *
     * @return array List of domains, that can access to this API
     */
    public static function allowedDomains() {
        return [
            // '*',                        // star allows all domains
            'http://social-web',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [

            // For cross-domain AJAX request
            'corsFilter'  => [
                'class' => \yii\filters\Cors::class,
                'cors'  => [
                    // restrict access to domains:
                    'Origin'                           => static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
                ],
            ],

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (in_array($action->id, ['index'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $params = json_decode(Yii::$app->request->getRawBody(), true);
        $author  = $params['author'];
        $recipient  = $params['recipient'];
        $in = [$author['id'], $recipient['id']];
        $messages = (new Query())
            ->select(['message', 'idauthor'])
            ->from(['c' => 'chat'])
            ->where([
                'idauthor' => $in,
                'idrecipient' => $in
            ])
            ->limit(30)
            ->orderBy('date_write')
            ->all();

        $response = $this->renderPartial('_chat_message', compact('messages', 'author', 'recipient'));
        Yii::$app->response->format = Response::FORMAT_JSON;
        return json_encode(compact('response'), JSON_UNESCAPED_UNICODE);
    }
}
