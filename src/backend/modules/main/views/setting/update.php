<?php

use backend\widgets\CKEditor;
use common\components\ActiveForm;
use common\models\Setting;
use yii\helpers\Html;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model \common\models\Setting */

$this->title = $model->isNewRecord ? "Создание настройки" : 'Обновление настройки: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'settings-form',
    'enableAjaxValidation' => true,
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'section_id')->dropDownList(Setting::$settingsTypes) ?>

    <?= ($model->isNewRecord) ?
        $form->field($model, 'code')->textInput(['maxlength' => 50]) :
        $form->field($model, 'code')->textInput(['id' => 'disabledInput', 'disabled' => 'disabled'])
    ?>

    <?php if ($model->isNewRecord): ?>
        <h4>Перед созданием настройки определите тип поля</h4>

        <?= $form->field($model, 'entity')->hiddenInput(['value' => 'Не заполнено']) ?>

    <?php else: ?>

        <?php if ($model->getElement() == 'text'): ?>

            <?= $form->field($model, 'entity')->textInput() ?>

        <?php elseif ($model->getElement() == 'editor'): ?>

            <?= $form->field($model, 'entity', ['options' => ['class' => 'form-group']])
                ->widget(CKEditor::class, [
                    'id' => 'content',
                    'preset' => 'custom',
                    'kcfinder' => true,
                    'clientOptions' => [
                        'allowedContent' => true,
                        'customConfig' => Yii::$app->params['urlToCke'],
                    ],
                ]);
            ?>

        <?php else: ?>

            <?= $form->field($model, 'entity')->textarea() ?>

        <?php endif; ?>
    <?php endif; ?>

    <?= ($model->isNewRecord) ?
        $form->field($model, 'input_type')
            ->dropDownList([
                'text' => 'Text',
                'textarea' => 'Textarea',
                'editor' => 'Editor',
            ]) : ''
    ?>

</div>

<div class="modal-footer">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'status')->widget(SwitchInput::class, [
                'pluginOptions' => [
                    'size' => 'medium',
                    'onColor' => 'success',
                    'onText' => 'Видно',
                    'offText' => 'Скрыто',
                ]
            ])->label(false); ?>
        </div>
        <div class="col-md-6">
            <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => 'btn pull-right btn-success']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

