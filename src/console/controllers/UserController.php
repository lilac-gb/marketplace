<?php

namespace console\controllers;

class UserController extends \yii\console\Controller
{
    public function actionGenerateAdmin() {
        $model = new \common\models\User();
        $model->username = 'admin';
        $model->email = 'admin@marketplace.docker';
        $model->status = \common\models\User::STATUS_ACTIVE;
        $model->setPassword('admin');
        $model->generateAuthKey();
        $model->save();
    }
}