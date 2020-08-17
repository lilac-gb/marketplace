<?php

namespace common\components;

use Yii;

class ActiveForm extends \yii\widgets\ActiveForm
{
    public static function validate($model, $attributes = null)
    {
        $errors = parent::validate($model, $attributes);

        if ($model instanceof ActiveRecordI18N) {
            $errors = array_merge(
                $errors,
                $model->validateTranslations(Yii::$app->request->post())
            );
        }

        return $errors;
    }
}