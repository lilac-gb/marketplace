<?php

namespace backend\modules\main\controllers;

use backend\components\ActiveController;
use yii\widgets\ActiveForm;
use common\models\Setting;
use yii\bootstrap\Modal;
use Yii;

/**
 * SettingController implements the CRUD actions for Setting model.
 */
class SettingController extends ActiveController
{
    public $modelClass = 'common\models\Setting';
    public $modalSize = Modal::SIZE_DEFAULT;
}
