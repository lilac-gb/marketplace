<?php

use common\components\ActiveForm;
use common\models\AdSection;
use common\models\AdType;
use common\models\User;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Ad */

$this->title = $model->isNewRecord ? "Создание модуля" : 'Редактирование объявления: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Ad', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

?>

    <h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'ad-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
    <div class="modal-body">

        <div class="row">
            <div class="col-md-6">
                <i title="Раздел по которому относится объявление"
                   class="glyphicon glyphicon-question-sign"></i>
                <?= $form->field($model, 'section_id')->dropDownList(AdSection::list()) ?>
            </div>
            <div class="col-md-6">
                <i title="Тип объявления"
                   class="glyphicon glyphicon-question-sign"></i>
                <?= $form->field($model, 'type_id')->dropDownList(AdType::list()) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <i title="Время создания" class="glyphicon glyphicon-question-sign"></i>
                <?= $form->field($model, 'created_at', ['options' => ['class' => 'form_group']])
                    ->widget(DateTimePicker::class, [
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy hh:ii',
                        ],
                    ]) ?>
            </div>

            <!--TODO make it like drop down 15 days, 30 days, always -->
            <div class="col-md-6">
                <i title="Время окончания объявления"
                   class="glyphicon glyphicon-question-sign"></i>
                <?= $form->field($model, 'ended_at',
                    ['options' => ['class' => 'form_group']])
                    ->widget(DateTimePicker::class, [
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy hh:ii',
                        ],
                    ]) ?>
            </div>
        </div>

        <i title="Может быть только тот, кто является автором" class="glyphicon glyphicon-question-sign"></i>
        <?= $form->field($model, 'user_id')
            ->widget(Select2::class, [
                'id' => 'ad-user',
                'data' => User::list(),
                'options' => [
                    'id' => 'user_id',
                ],
            ])->hint('Выберите автора заказа') ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 250]) ?>

        <?= $form->field($model, 'description', ['options' => ['class' => 'form-group']])->textarea() ?>
        <?= $form->field($model, 'views')->textInput(['maxlength' => 250]) ?>

        <?= $form->field($model, 'url_site')->textInput(['maxlength' => 250]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'life_time')->textInput(['maxlength' => 250]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'price')->textInput(['maxlength' => 250]) ?>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'status')->widget(SwitchInput::class, [
                    'id' => 'ad-status-update',
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