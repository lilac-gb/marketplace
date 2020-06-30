<?php

use yii\db\Migration;

/**
 * Class m191219_072608_table_menus_links
 */
class m191219_072608_table_menus_links extends Migration
{
    private $tableName = '{{%menus_links}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer()->notNull()->defaultValue(0),
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'url' => $this->string()->notNull(),
            'class' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'order' => $this->integer()->notNull()->defaultValue(0),
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

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191219_072608_table_menus_links cannot be reverted.\n";

        return false;
    }
    */
}
