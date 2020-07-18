<?php
namespace api\modules\v1\controllers;

use api\components\actions\ImageAttachmentAction;
use api\components\ActiveController;
use common\components\ActiveForm;
use common\models\forms\ChangePasswordForm;
use common\models\forms\LoginForm;
use common\models\User;
use common\services\UserService;
use Yii;
use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


class UserController extends ActiveController {
    public $modelClass = 'api\models\User';

    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'models',
    ];

    public function actions()
    {
        $actions = parent::actions();

        $actions['imgAttachApi'] = [
            'class' => ImageAttachmentAction::class,
            'types' => [
                'user' => User::class,
            ],
        ];

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['options', 'index', 'view'];
        $behaviors['authenticator']['optional'] = [
            'login',
            'signup',
            'recovery',
            'logout',
            'me',
            'check-activate-key', // POST token returns user
            'change-password',
            'activation-email',
            'imgAttachApi',
            'save',
        ];

        return $behaviors;
    }

    public function actionSave()
    {
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        $postData = Yii::$app->request->post();

        $user->load($postData);
        $user->save();

        return $user;
    }

    public function actionMe() {
        return Yii::$app->user->identity;
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException();
        }

        $request = Yii::$app->request;

        $loginForm = new LoginForm();

        $loginForm->load($request->post(), '');

        if ($loginForm->validate() && $loginForm->login()) {
            Yii::$app->user->refreshToken();

            return Yii::$app->user->identity;
        }

        return $loginForm->errors;
    }

    public function actionCheckActivateKey(): array
    {
        $request = Yii::$app->request;
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();

        if (!($token = $request->post('token'))) {
            return [
                'statusCode' => 400,
                'errors' => ['system' => 'Please provide token'],
            ];
        }

        $user->save(false);

        return [
            'statusCode' => 200,
            'data' => [
                'valid' => true,
            ],
        ];
    }

    public function actionChangePassword()
    {
        $request = Yii::$app->request;
        $changePasswordForm = new ChangePasswordForm();
        $changePasswordForm->oldPassword = $request->post('oldPassword');
        $changePasswordForm->newPassword = $request->post('newPassword');

        if ($changePasswordForm->validate()) {
            $changePasswordForm->changePassword();

            Yii::$app->user->refreshToken();

            return [];
        }

        return $changePasswordForm;
    }

    private function generateNickname($nik)
    {
        $exists = User::find()->where(['username' => $nik])->exists();
        if ($exists) {
            $newNick = $nik . '-' . substr(uniqid('', true), -5);

            return $this->generateNickname($newNick);
        }

        return $nik;
    }

    public function actionSignup()
    {
        if (!\Yii::$app->user->isGuest) {
            return ['redirect' => Url::to('/')];
        }

        $data = Yii::$app->request->post('data');

        $model = new User();
        $model->scenario = User::SCENARIO_SIGN_UP;
        $model->username = $this->generateNickname(explode('@', $data['email'])[0]);
        $model->password_hash = '';
        $model->confirmation_secret = Yii::$app->security->generateRandomString(10);

        if ($model->load($data, '')) {

            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            //$model->setPassword($model->password);
            $model->generateEmailVerificationToken();
            $model->status = User::STATUS_EMAIL_NC;
            $model->role = User::ROLE_GUEST;

            if ($model->validate()) {
                $transaction = $model->getDb()->beginTransaction();

                $model->save(false);
                $model->refresh();

                $transaction->commit();

                UserService::sendActivationEmail($model);

                return ['redirect' => Url::to('/')];
            } else {
                return $model->errors;
            }
        }

        return [
            'statusCode' => 400,
            'errors' => $model->errors,
        ];
    }

    public function actionRecovery()
    {
        $email = Yii::$app->request->post('email');

        if (!$email) {
            return [
                'statusCode' => 400,
                'errors' => ['email' => 'Пожалуйста, заполните email'],
            ];
        }

        $user = User::findOne(['email' => $email]);

        if (!$user) {
            return [
                'statusCode' => 400,
                'errors' => ['email' => 'Такого пользователя не существует'],
            ];
        }

        $user->generatePasswordResetToken();

        if ($user->save(false, ['password_reset_token'])) {
            UserService::sendRestorePasswordEmail($user);

            return [
                'statusCode' => 200,
            ];
        }

        return [
            'statusCode' => 400,
            'errors' => ['system' => 'Произошла ошибка, попробуйте позже!'],
        ];
    }

    public function actionActivationEmail($id)
    {
        $user = User::findOne($id);

        if (empty($user)) {
            throw new NotFoundHttpException();
        }

        $hash = Yii::$app->request->post('hash');

        try {
            $confirmationHash = base64_decode($hash);
            $email = Yii::$app->security->decryptByKey($confirmationHash, $user->confirmation_secret);

            if (!$email) {
                throw new ErrorException();
            }
        } catch (ErrorException $e) {
            throw new BadRequestHttpException();
        }

        $password = Yii::$app->security->generateRandomString(8);

        $user->updateAttributes([
            'status' => User::STATUS_ACTIVE,
            'role' => User::ROLE_USER,
            'password_hash' => Yii::$app->security->generatePasswordHash($password),
            'confirmation_secret' => '',
        ]);

        UserService::sendPassword($user, $password);

        return ['status' => 200];
    }

    public function actionLogout()
    {
        return Yii::$app->user->logout();
    }

    public function actionSpecialUser()
    {
        $nickname = ltrim(Yii::$app->request->url, '/');
        if (!is_numeric($nickname)) {
            $nickname = ltrim($nickname, '@');
            $user = \api\models\User::find()
                ->joinWith("details")
                ->where(['username' => $nickname])->one();

            return $user;
        }

        return false;
    }
}
