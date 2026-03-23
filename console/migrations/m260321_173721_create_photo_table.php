<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%photo}}`.
 */
class m260321_173721_create_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%photo}}', [
            'id' => $this->primaryKey(),
            'album_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'url' => $this->string()->notNull()
        ]);
        
        $this->addForeignKey('fk-photo-album_id', '{{%photo}}', 'album_id', '{{%album}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-photo-album_id', '{{%photo}}', 'album_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-photo-album_id', '{{%photo}}');
        $this->dropIndex('idx-photo-album_id', '{{%photo}}');
        $this->dropTable('{{%photo}}');
    }
}
