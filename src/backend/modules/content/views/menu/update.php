<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = $model->isNewRecord ? "Создание меню" : 'Обновление меню #'.$model->id;
?>

<h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'menu-form',
    'enableAjaxValidation' => true,
]); ?>
    <div class="modal-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 500]) ?>
        <?= $form->field($model, 'title')->textInput(['maxlength' => 550]) ?>
    </div>

<div class="modal-footer">
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'status')->widget(SwitchInput::class, [
                'pluginOptions' => [
                    'size' => 'medium',
                    'onColor' => 'success',
                    'onText' => 'Видно',
                    'offText' => 'Скрыто',
                ]
            ])->label(false); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'levels')->widget(SwitchInput::class, [
                'pluginOptions' => [
                    'size' => 'medium',
                    'onColor' => 'success',
                    'onText' => '2 уровня',
                    'offText' => '1 уровень',
                ]
            ])->label(false); ?>
        </div>
        <div class="col-md-4">
            <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => 'btn pull-right btn-success']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>


