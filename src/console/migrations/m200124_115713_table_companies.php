<?php

use yii\db\Migration;

/**
 * Class m200124_115713_table_companies
 */
class m200124_115713_table_companies extends Migration
{
    private $companies = '{{%companies}}';

    public function safeUp()
    {
        $this->createTable($this->companies, [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->defaultValue(NULL),
            'description' => $this->text(),
            'owner_id' => $this->integer()->defaultValue(NULL),
            'role' => $this->smallInteger()->notNull()->defaultValue(0),
            'url' => $this->string(255)->defaultValue(null),
            'site' => $this->string(255)->defaultValue(null),
            'phone' => $this->string(40)->notNull(),
            'email' => $this->string(100)->notNull(),
            'vat' => $this->string(15)->defaultValue(null),
            'id_number' => $this->string(15)->defaultValue(null),
            'working_days' => $this->string(50)->defaultValue(null),
            'time_from' => $this->string(20)->defaultValue(null),
            'time_to' => $this->string(20)->defaultValue(null),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
            'views' => $this->integer()->notNull()->defaultValue(0),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0; DROP TABLE ' . $this->companies . '; SET FOREIGN_KEY_CHECKS=1;');
    }
}
