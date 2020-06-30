<?php
namespace api\controllers;

use api\components\Controller;

class MainController extends Controller
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['optional'][] = 'index';

        return $behaviors;
    }

    public function actionIndex() {
        return ['message' => 'ok'];
    }
}