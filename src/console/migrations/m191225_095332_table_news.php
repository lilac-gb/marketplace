<?php

use yii\db\Migration;

/**
 * Class m191225_095332_table_news
 */
class m191225_095332_table_news extends Migration
{
    public $tableName = '{{%news}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),

            'url' => $this->string()->notNull()->defaultValue(''),
            'name' => $this->string()->notNull()->defaultValue(''),
            'anons' => $this->text()->notNull(),
            'description' => $this->text()->notNull(),

            'views' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(0),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'published_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
