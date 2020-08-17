<?php

namespace backend\modules\user\controllers;

use backend\components\ActiveController;
use yii\bootstrap\Modal;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public $modalSize = Modal::SIZE_DEFAULT;
}
