<?php

use yii\db\Migration;

/**
 * Class m191219_072536_table_pages
 */
class m191219_072536_table_pages extends Migration
{
    private $tableName = '{{%pages}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull()->defaultValue(0),
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
