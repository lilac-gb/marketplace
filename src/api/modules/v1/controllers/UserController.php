<?php
namespace api\modules\v1\controllers;

use api\components\ActiveController;
use common\models\forms\LoginForm;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use api\components\User;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;

class UserController extends ActiveController {
    public $modelClass = 'api\models\User';

    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'models',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['options', 'index', 'view'];
        $behaviors['authenticator']['optional'] = ['login'];

        return $behaviors;
    }

    public function actionMe() {
        return Yii::$app->user->identity;
    }

    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException();
        }

        Yii::$app->user->on(User::EVENT_AFTER_LOGIN, function($event) {
            $cookies = Yii::$app->response->cookies;
            $token = (string) Yii::$app->user->refreshToken();

            $cookie = new \yii\web\Cookie([
                'name' => 'token',
                'value' => $token,
                'domain' => Yii::$app->params['domains']['cookie'],
                'secure' => true,
            ]);

            $cookies->add($cookie);

            return true;
        });

        $form = new LoginForm();

        if ($form->load(Yii::$app->request->post(), '') && $form->login()) {
            return Yii::$app->user->identity;
        }

        return $form;
    }
}
