<?php

use yii\db\Migration;

/**
 * Class m200708_212200_table_ads
 */
class m200708_212200_table_ads extends Migration
{
    private $ads = '{{%ads}}';
    private $adsSection = '{{%ads_sections}}';
    private $adsType = '{{%ads_types}}';
    public $orders = '{{%orders}}';
    public $ordersItems = '{{%orders_items}}';

    public function safeUp()
    {
        $this->createTable($this->ads, [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->null(),
            'section_id' => $this->integer()->null(),
            'user_id' => $this->integer(),
            'name' => $this->string(200),
            'url' => $this->string(255),
            'price' => $this->decimal(10, 2)->null(),
            'description' => $this->text(),
            'ended_at' => $this->integer()->defaultValue(0),
            'life_time' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
            'views' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(0),
            'url_site' => $this->string(250),
        ]);

        $this->createTable($this->adsSection, [
            'id' => $this->primaryKey(),
            'icon' => $this->string()->defaultValue(null),
            'freq' => $this->integer(),
            'status' => $this->integer()->defaultValue(0),
            'name' => $this->string(250)->null(),
            'url' => $this->string(255)->unique(),
        ]);

        $this->addForeignKey(
            'fk_ads_sections',
            $this->ads,
            'section_id',
            $this->adsSection,
            'id',
            'SET NULL'
        );

        $this->createTable($this->adsType, [
            'id' => $this->primaryKey(),
            'icon' => $this->string(),
            'freq' => $this->integer(),
            'status' => $this->integer()->defaultValue(0),
            'name' => $this->string(250)->null(),
            'url' => $this->string(255)->unique(),
        ]);

        $this->addForeignKey(
            'fk_ads_types',
            $this->ads,
            'type_id',
            $this->adsType,
            'id',
            'SET NULL'
        );

        $this->createTable($this->orders, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'address' => $this->string(300)->null(),
            'ip' => $this->string(100)->null(),
            'phone' => $this->string(300)->null(),
            'email' => $this->string(300)->null(),
            'name' => $this->string(300)->null(),
            'text' => $this->text(),
            'status' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
        ]);

        $this->createTable($this->ordersItems, [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'model_id' => $this->integer(),
            'count' => $this->integer(),
            'price' => $this->decimal(10, 2)->null(),
            'status' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
        ]);

        $this->createIndex('orders_index', $this->orders, ['id'], $unique = true);
        $this->createIndex('orders_items_index', $this->ordersItems, ['id'], $unique = true);

        $this->addForeignKey(
            'fk_orders_items',
            $this->ordersItems,
            'order_id',
            $this->orders,
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0; DROP TABLE ' . $this->adsType . '; SET FOREIGN_KEY_CHECKS=1;');
        $this->execute('SET FOREIGN_KEY_CHECKS=0; DROP TABLE ' . $this->adsSection . '; SET FOREIGN_KEY_CHECKS=1;');
        $this->execute('SET FOREIGN_KEY_CHECKS=0; DROP TABLE ' . $this->ads . '; SET FOREIGN_KEY_CHECKS=1;');
        $this->execute('SET FOREIGN_KEY_CHECKS=0; DROP TABLE ' . $this->ordersItems . '; SET FOREIGN_KEY_CHECKS=1;');
        $this->dropTable($this->orders);
    }
}
