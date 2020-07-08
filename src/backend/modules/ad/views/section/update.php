<?php

use common\components\ActiveForm;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdSection */

$this->title = $model->isNewRecord ? "Добавление раздела" : 'Обновление раздела #' . $model->id;
?>

<h4><?= Html::encode($this->title) ?></h4>
<script src="<?= Yii::$app->params['urlToSyncTranslit'] ?>"></script>

<?php $form = ActiveForm::begin([
    'id' => 'section-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => 250]) ?>
    <?= $form->field($model, 'icon')->textInput(['maxlength' => 250]) ?>
</div>

<div class="modal-footer">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'status')->widget(SwitchInput::class, [
                'pluginOptions' => [
                    'size' => 'medium',
                    'onColor' => 'success',
                    'onText' => 'Показать',
                    'offText' => 'Скрыть',
                ],
            ])->label(false); ?>
        </div>
        <div class="col-md-6">
            <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => 'btn pull-right btn-success']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
