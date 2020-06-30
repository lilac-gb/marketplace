<?php

use yii\db\Migration;

/**
 * Class m200127_110108_table_auth_networks
 */
class m200127_110108_table_auth_networks extends Migration
{
    public $tableName = '{{%auth_networks}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'network' => $this->string()->notNull(),
            'network_id' => $this->string()->notNull(),
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
