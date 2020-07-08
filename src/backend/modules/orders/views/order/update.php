<?php

use common\models\Ad;
use common\models\Publication;
use common\models\Order;
use common\models\User;
use dosamigos\ckeditor\CKEditor;
use kartik\switchinput\SwitchInput;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model common\models\Order */
/* @var $this yii\web\View */

$this->title = $model->isNewRecord ? "Создание заявки" : 'Изменение заявки - ' . $model->id;
?>

<h4><?= Html::encode($this->title) ?></h4>

<?php $form = ActiveForm::begin([
    'id' => 'order-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
<div class="modal-body">

    <div id="main-info">
        <div class="row">
            <div class="col-sm-12">
                <?php if (!$model->isNewRecord): ?><h4>Заявка: <?= $model->id ?></h4><?php endif; ?>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'name', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250, 'disabled' => !$model->isNewRecord ? true : false]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'email', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250, 'disabled' => !$model->isNewRecord ? true : false]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'phone', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250, 'disabled' => !$model->isNewRecord ? true : false]) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'address', ['options' => ['class' => 'form-group']])->textInput(['maxlength' => 250, 'disabled' => !$model->isNewRecord ? true : false]) ?>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'user_id')->widget(Select2::class, [
                            'data' => User::list(),
                            'options' => [
                                'id' => 'update-user_id',
                                'disabled' => !$model->isNewRecord ? 'disabled' : false,
                                'placeholder' => 'Выберите пользователя...',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false,
                            ],
                        ]) ?>
                    </div>
                </div>
                <hr/>
                <?= $form->field($model, 'text', ['options' => ['class' => 'form-group']])->textarea() ?>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    Номер заказа
                </th>
                <th>
                    ФИО
                </th>
                <th>
                    Стоимость
                </th>
                <th>
                    Кол-во
                </th>
                <th>
                    Итого
                </th>
                <th>
                    Управление
                </th>
            </tr>
            </thead>
            <tbody>

            <?php
            /** @var {$item|$items} \common\models\OrderItem */
            foreach ($items as $item):
                $ad = Ad::findOne(['id' => $item->model_id]);
                ?>
                <tr id="good-<?= $ad->id ?>">
                    <td>
                        <?= $ad->id ?>
                    </td>
                    <td>
                        <?= $ad->name ?>
                    </td>
                    <td>
                        <?= $item->price ?> €
                    </td>
                    <td>
                        <?= $item->count ?>
                    </td>
                    <td>
                        <?= $item->count * $item->price ?> €
                    </td>
                    <td>
                        <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', '#', ['class' => 'btn btn-danger btn-sm', 'onclick' => "removeItem($item->id)"]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal-footer">
    <div class="row">
        <div class="col-md-12 text-center">
            <?= $form->field($model, 'status', ['options' => ['class' => 'form_group']])
                ->widget(SwitchInput::class, [
                    'id' => 'status_update',
                    'type' => SwitchInput::RADIO,
                    'items' => [
                        ['label' => 'В работе', 'value' => Order::STATUS_PROCESS],
                        ['label' => 'Выполнено', 'value' => Order::STATUS_DONE],
                        ['label' => 'Доставка', 'value' => Order::STATUS_SHIPPING],
                        ['label' => 'Удалить', 'value' => Order::STATUS_DELETED],
                    ],
                    'pluginOptions' => [
                        'size' => 'small',
                        'onColor' => 'success',
                        'offColor' => 'default',
                        'onText' => 'Да',
                        'offText' => 'Нет',
                    ],
                    'containerOptions' => ['style' => 'display:inline-block;margin-top: 40px;'],
                    'labelOptions' => ['style' => 'font-size: 12px;position: absolute;margin-top: -22px;'],
                ])->label(false); ?>


        </div>

    </div>
    <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Отправить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>

<script>
    removeItem = id => {
        console.log(id)
    }
</script>