<?php

namespace console\controllers;

use common\models\AdSection;
use common\models\AdType;
use common\models\User;
use tebazil\yii2seeder\Seeder;
use Yii;

class SeederController extends \yii\console\Controller
{
    private $seeder;
    private $generator;
    private $faker;

    public function actionIndex() {
        $this->seeder = new Seeder('db');
        $this->generator = $this->seeder->getGeneratorConfigurator();
        $this->faker = $this->generator->getFakerConfigurator();

        $this->generateUsers(10);
        $this->generatePublications(100);
        $this->generateAds(100);

        $this->seeder->refill();
    }

    private function generateUsers($count = 5) {
        $pass = 123;

        $this->seeder->table('users')->columns([
            'id',
            'username' => $this->faker->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'status' => User::STATUS_ACTIVE,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash($pass),
            'email' => $this->faker->email,
            'created_at' => $this->faker->unixTime,
            'updated_at' => $this->faker->unixTime,
            'role' => User::ROLE_USER,
        ])->rowQuantity($count);
    }

    private function generatePublications($count = 50) {
        $this->seeder->table('news')->columns([
            'id',
            'user_id' => $this->generator->relation('users', 'id'),
            'anons' => $this->faker->sentence(2),
            'name' => $this->faker->sentence(7),
            'description' => $this->faker->paragraph(100),
            'status' => 1,
            'url' => $this->faker->slug(10),
            'created_at' => $this->faker->unixTime,
            'updated_at' => $this->faker->unixTime,
            'views' => $this->faker->randomNumber(6),
        ])->rowQuantity($count);
    }

    private function generateAds($count = 50)
    {
        AdSection::deleteAll([]);
        AdType::deleteAll([]);

        $sections = [
            [
                'id' => 1,
                'name' => 'Товары',
                'url' => 'goods',
            ],
            [
                'id' => 2,
                'name' => 'Услуги',
                'url' => 'services',
            ],
        ];
        $types = [
            [
                'id' => 1,
                'name' => 'Продажа',
                'url' => 'sell',
            ],
            [
                'id' => 2,
                'name' => 'Покупка',
                'url' => 'purchase',
            ],
        ];

        foreach ($sections as $section) {
            $adSection = new AdSection();
            $adSection->id = $section['id'];
            $adSection->name = $section['name'];
            $adSection->url = $section['url'];
            $adSection->save(false);
        }

        foreach ($types as $type) {
            $adType = new AdType();
            $adType->id = $type['id'];
            $adType->name = $type['name'];
            $adType->url = $type['url'];
            $adType->save(false);
        }

        $this->seeder->table('ads')->columns([
            'id',
            'type_id' => rand(1, 2),
            'section_id' => rand(1, 2),
            'user_id' => $this->generator->relation('users', 'id'),
            'name' => $this->faker->sentence(7),
            'description' => $this->faker->paragraph(100),
            'price' => $this->faker->randomFloat(2, 30, 5000),
            'status' => 1,
            'url' => $this->faker->slug(10),
            'created_at' => $this->faker->unixTime,
            'updated_at' => $this->faker->unixTime,
            'views' => $this->faker->randomNumber(6),
        ])->rowQuantity($count);
    }
}