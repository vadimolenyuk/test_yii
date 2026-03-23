<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%album}}`.
 */
class m260321_173638_create_album_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%album}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull()
        ]);

        $this->addForeignKey('fk-album-user_id', '{{%album}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-album-user_id', '{{%album}}', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-album-user_id', '{{%album}}');
        $this->dropIndex('idx-album-user_id', '{{%album}}');
        $this->dropTable('{{%album}}');
    }
}
