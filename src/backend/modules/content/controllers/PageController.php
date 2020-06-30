<?php

namespace backend\modules\content\controllers;

use backend\components\ActiveController;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use common\models\Page;
use Yii;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends ActiveController
{
    public $modelClass = 'common\models\Page';
    public $modalSize = Modal::SIZE_DEFAULT;
}
