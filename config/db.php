<?php

$db = [];

$ovDB = __DIR__ . '/override/db.php';
if (file_exists($ovDB)) {
    $db = require $ovDB;
}

return $db + [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=social_web',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',

        // Schema cache options (for production environment)
        //'enableSchemaCache' => true,
        //'schemaCacheDuration' => 60,
        //'schemaCache' => 'cache',
    ];
