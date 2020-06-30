<?php

use yii\db\Migration;

/**
 * Class m191219_065313_table_settings
 */
class m191219_065313_table_settings extends Migration
{
    public $tableName = '{{%settings}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'section_id' => $this->string()->notNull(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'entity' => $this->text()->notNull(),
            'input_type' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
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
