<?php

use yii\db\Migration;

/**
 * Class m200711_101148_add_users_column
 */
class m200711_101148_add_users_column extends Migration
{
    private $users = '{{%users}}';

    public function safeUp()
    {
        $this->addColumn($this->users, 'confirmation_secret', $this->string());
        $this->addColumn($this->users, 'role', $this->string()->defaultValue('guest'));
    }

    public function safeDown()
    {
        $this->dropColumn($this->users, 'confirmation_secret');
        $this->dropColumn($this->users, 'role');
    }
}
