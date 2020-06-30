<?php

use common\components\ActiveForm;
use kartik\widgets\SwitchInput;
use common\models\MenuLink;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MenuLink */
/* @var $menu common\models\Menu */

$this->title = $model->isNewRecord ? "Создание пункта меню" : 'Обновление пункта меню #'.$model->id;
?>

<h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'menu-form',
    'enableAjaxValidation' => true,
]); ?>
    <div class="modal-body">
        <?php if($menu->levels): ?>
        <?= $form->field($model, 'parent_id')->dropDownList(MenuLink::getParents($model->menu_id, $model->id), ['prompt' => '']) ?>
        <?php endif; ?>

        <?= $form->field($model, 'title')->textInput() ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => 500]) ?>
        <?= $form->field($model, 'class')->textInput(['maxlength' => 250]) ?>

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

