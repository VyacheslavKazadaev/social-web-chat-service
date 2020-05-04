<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chat}}`.
 */
class m200314_100919_create_chat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chat}}', [
            'idchat' => $this->primaryKey()->unsigned()->notNull(),
            'message' => $this->string(3000)->notNull(),
            'date_write' => $this->dateTime()->notNull(),
            'idauthor' => $this->integer()->unsigned()->notNull(),
            'idrecipient' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex('in_chat$idauthor$idrecipient', '{{%chat}}', ['idauthor', 'idrecipient']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chat}}');
    }
}
