<?php

use yii\db\Migration;

/**
 * Class m191219_072549_table_menus
 */
class m191219_072549_table_menus extends Migration
{
    private $tableName = '{{%menus}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'levels' => $this->smallInteger()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191219_072549_table_menus cannot be reverted.\n";

        return false;
    }
    */
}
