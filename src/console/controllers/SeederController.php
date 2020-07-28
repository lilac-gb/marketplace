<?php

namespace console\controllers;

use Yii;

class SeederController extends \yii\console\Controller
{
    private $seeder;
    private $generator;
    private $faker;

    public function actionIndex() {
        $this->seeder = new \tebazil\yii2seeder\Seeder('db');
        $this->generator = $this->seeder->getGeneratorConfigurator();
        $this->faker = $this->generator->getFakerConfigurator();

        $this->generateUsers(10);
        $this->generatePublications(200);

        $this->seeder->refill();
    }

    private function generateUsers($count = 5) {
        $pass = 123;

        $this->seeder->table('users')->columns([
            'id',
            'username' => $this->faker->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'status' => \common\models\User::STATUS_ACTIVE,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash($pass),
            'email' => $this->faker->email,
            'created_at' => $this->faker->unixTime,
            'updated_at' => $this->faker->unixTime,
        ])->rowQuantity($count);
    }

    private function generatePublications($count = 50) {
        $this->seeder->table('news')->columns([
            'id',
            'user_id' => $this->generator->relation('users', 'id'),
            'anons' => 'Аннонс',
            'name' => $this->faker->sentence(7),
            'description' => $this->faker->paragraph(100),
            'status' => 1,
            'url' => $this->faker->slug(10),
            'created_at' => $this->faker->unixTime,
            'updated_at' => $this->faker->unixTime,
            'views' => $this->faker->randomNumber(6),
        ])->rowQuantity($count);
    }
}