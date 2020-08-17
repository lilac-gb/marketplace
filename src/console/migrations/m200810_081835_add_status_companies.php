<?php

use yii\db\Migration;

/**
 * Class m200810_081835_add_status_companies
 */
class m200810_081835_add_status_companies extends Migration
{
    private $companies = '{{%companies}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->companies, 'status', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->companies, 'status');
    }
}
