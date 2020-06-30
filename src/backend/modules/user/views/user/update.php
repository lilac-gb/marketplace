<?php

use common\components\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = $model->isNewRecord ? "Создание пользователя" : 'Обновление пользователя #' . $model->id;
?>

<h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'user-form',
    'enableAjaxValidation' => true,
]); ?>
<div class="modal-body">
    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">

            <?= $form->field($model, 'username')->textInput() ?>

            <?= $form->field($model, 'first_name')->textInput() ?>

            <?= $form->field($model, 'last_name')->textInput() ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'password')->textInput() ?>

        </div>
    </div>
</div>

<div class="modal-footer">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'status')->widget(SwitchInput::class, [
                'pluginOptions' => [
                    'size' => 'medium',
                    'onColor' => 'success',
                    'onText' => 'Виден',
                    'offText' => 'Не виден',
                ]
            ])->label(false); ?>
        </div>
        <div class="col-md-6">
            <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => 'btn pull-right btn-success']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
