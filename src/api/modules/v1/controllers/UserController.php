<?php

namespace api\modules\v1\controllers;

use api\components\actions\ImageAttachmentAction;
use api\components\ActiveController;
use common\components\ActiveForm;
use common\models\forms\LoginForm;
use common\models\User;
use common\services\UserService;
use Yii;
use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class UserController extends ActiveController
{
    /**
     * @param string              $action
     * @param \api\models\User|null $model
     * @param array               $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        parent::checkAccess($action, $model, $params);
        if ((
                $action == 'update'
                || $action == 'delete'
                || $action == 'imgAttachApi'
            ) && $model->id != Yii::$app->user->id)
        {
            throw new ForbiddenHttpException();
        }
    }

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
            'restore',
            'logout',
            'me',
            'check-activate-key',
            'activation-email',
            'confirm-email',
            'imgAttachApi',
            'save',
        ];

        return $behaviors;
    }

    public function actionSave()
    {
        /** @var User $user */
        /** @var User $pass */
        $user = User::findOne(Yii::$app->user->id);
        $postData = Yii::$app->request->post();
        $oldEmail = $user->email;
        $user->scenario = User::SCENARIO_UPDATE;

        if (isset($postData['oldPassword'])
            && isset($postData['password'])
            && !empty($postData['oldPassword'])
            && !empty($postData['password'])
        ) {
            $pass = $user;
            $pass->scenario = User::SCENARIO_UPDATE_PASSWORD;

            if ($pass->load(Yii::$app->request->post(), '')) {
                if (!Yii::$app->security->validatePassword($postData['oldPassword'], $user->password_hash)) {
                    return [
                        'statusCode' => 400,
                        'errors' => [
                            'Старый пароль неверный',
                        ],
                    ];
                }

                $user->setPassword($postData['password']);
            }
        } else if ((isset($postData['oldPassword']) && !empty($postData['oldPassword']))
            || (isset($postData['password']) && !empty($postData['password']))
        ) {
            return [
                'statusCode' => 400,
                'errors' => [
                    'Для смены пароля, оба поля обязательны для заполнения',
                ],
            ];
        }

        if ($user->load($postData, '') && $user->validate()) {

            if ($user->email !== $oldEmail) {
                $confirmationSecret = Yii::$app->security->generateRandomString();
                $encrypt = Yii::$app->security->encryptByKey($user->email, $confirmationSecret);
                $confirmationHash = base64_encode($encrypt);

                UserService::sendChangeEmailConfirmation($user, $user->email, $confirmationHash);

                $user->updateAttributes([
                    'email' => $oldEmail,
                    'status' => User::STATUS_EMAIL_NC,
                    'confirmation_secret' => $confirmationSecret,
                ]);

                return [
                    'statusCode' => 200,
                    'message' => [
                        'Пожалуйста, активируйте Ваш новый email адрес через ссылку в письме!',
                    ],
                ];
            }

            if ($user->save(false)) {
                return [
                    'statusCode' => 200,
                    'message' => [
                        'Данные успешно сохранены!',
                    ],
                ];
            }

            return [
                'statusCode' => 400,
                'errors' => $user->errors,
            ];
        }

        return [
            'statusCode' => 400,
            'errors' => $user->errors,
        ];
    }

    public function actionMe()
    {
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

    private function generateNickname($nik)
    {
        $exists = User::find()->where(['username' => $nik])->exists();
        if ($exists) {
            $newNick = $nik . '-' . Yii::$app->security->generateRandomString(5);

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
                'errors' => ['Пожалуйста, заполните email'],
            ];
        }

        $user = User::findOne(['email' => $email]);

        if (!$user) {
            return [
                'statusCode' => 400,
                'errors' => ['Такого пользователя не существует'],
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
            'errors' => ['Произошла ошибка, попробуйте позже!'],
        ];
    }

    public function actionRestore()
    {
        $token = Yii::$app->request->post('token');

        if (!$token) {
            return [
                'statusCode' => 400,
                'errors' => ['Нет токена!'],
            ];
        }

        $user = User::findOne(['password_reset_token' => $token]);

        if (!$user) {
            return [
                'statusCode' => 400,
                'errors' => ['Такого пользователя не существует!'],
            ];
        }

        $password = Yii::$app->security->generateRandomString(8);

        $user->setPassword($password);
        $user->password_reset_token = null;

        if ($user->save(false)) {
            UserService::sendPassword($user, $password);

            return [
                'statusCode' => 200,
            ];

        } else {
            return [
                'statusCode' => 400,
                'errors' => ['Что-то пошло не так!'],
            ];
        }
    }

    public function actionActivationEmail($id)
    {
        $user = User::findOne($id);

        if (empty($user)) {
            throw new NotFoundHttpException('Такого пользователя не существует');
        }

        $hash = Yii::$app->request->post('hash');

        try {
            $confirmationHash = base64_decode(str_replace(' ', '+', $hash));

            $email = Yii::$app->security->decryptByKey($confirmationHash, $user->confirmation_secret);

            if (!$email) {
                throw new ErrorException('Ошибка токена');
            }
        } catch (ErrorException $e) {
            throw new BadRequestHttpException('Неверный токен!');
        }

        $password = Yii::$app->security->generateRandomString(8);

        $user->updateAttributes([
            'status' => User::STATUS_ACTIVE,
            'role' => User::ROLE_USER,
            'password_hash' => Yii::$app->security->generatePasswordHash($password),
            'confirmation_secret' => '',
        ]);

        Yii::$app->user->login($user);

        Yii::$app->user->refreshToken();

        UserService::sendPassword($user, $password);

        return Yii::$app->user->identity;
    }

    public function actionConfirmEmail($id)
    {
        $user = User::findOne($id);

        if (empty($user)) {
            throw new NotFoundHttpException('Нет такого пользователя');
        }

        $hash = Yii::$app->request->post('hash');

        try {
            $confirmationHash = base64_decode(str_replace(' ', '+', $hash));

            $email = Yii::$app->security->decryptByKey($confirmationHash, $user->confirmation_secret);

            if (!$email) {
                throw new ErrorException('Ошибка токена');
            }
        } catch (ErrorException $e) {
            throw new BadRequestHttpException('Неверный токен!');
        }

        $user->updateAttributes([
            'email' => $email,
            'status' => User::STATUS_ACTIVE,
            'confirmation_secret' => '',
        ]);

        return $user;
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
