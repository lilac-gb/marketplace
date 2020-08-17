<?php


use backend\widgets\CKEditor;
use common\components\ActiveForm;
use common\models\Company;
use kartik\widgets\TimePicker;
use backend\widgets\MetaTags;
use common\components\Tabs;
use kartik\widgets\Select2;
use common\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Company */

$this->title = $model->isNewRecord ? "Создание" : 'Обновление #' . $model->id;

?>

<h4><?= Html::encode($this->title) ?></h4>
<script src="<?= Yii::$app->params['urlToSyncTranslit'] ?>"></script>

<?php $form = ActiveForm::begin([
    'id' => 'pages-form',
    'enableAjaxValidation' => true,
]); ?>
<div class="modal-body">
    <?= Tabs::widget([
        'options' => ['class' => 'nav-tabs'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Информация', 'options' => ['id' => 'info']],
            ['label' => 'Мета', 'options' => ['id' => 'meta']],
        ],
        'renderTabContent' => false,
    ]); ?>
    <br>
    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">
            <div class="article-visible">
                <?= $form->field($model, 'owner_id')->widget(Select2::class, [
                    'data' => [
                        0 => 'Без автора',
                        'Пользователи' => User::list()
                    ],
                    'options' => [
                        'id' => 'owner_id',
                    ],
                ])->hint('Выберите владельца компании') ?>
            </div>

            <?= $form->field($model, 'name')->textInput() ?>

            <div class="input-group special-input-group">
                <?= $form->field($model, 'url', ['options' => ['class' => 'form-group']])
                    ->textInput(['disabled' => !$model->isNewRecord, 'placeholder' => 'URL генерируется при вводе названия'])
                    ->label(false);
                echo '<span class="input-group-btn">
                            <button class="btn open-url btn-default" type="button">
                                    <i class="glyphicon glyphicon-pencil"></i>
                            </button>
                      </span>'
                ?>
            </div>

            <i title="Само описание, визуально может отличаться от того, что будет на странице"
               class="glyphicon glyphicon-question-sign"></i>
            <?= $form->field($model, 'description', ['options' => ['class' => 'form-group']])
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

            <?= $form->field($model, 'vat')->textInput() ?>

            <?= $form->field($model, 'id_number')->textInput() ?>

            <?= $form->field($model, 'site')->textInput() ?>

            <?= $form->field($model, 'phone')->textInput() ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <div class="row">
                <div class="col-sm-6">
                    <i title="Время начала дня" class="glyphicon glyphicon-question-sign"></i>
                    <?= $form->field($model, 'time_from', ['options' => ['class' => 'form_group']])
                        ->widget(TimePicker::class, [
                            'pluginOptions' => [
                                'showSeconds' => false,
                                'showMeridian' => false,
                                'defaultTime' => '09:00',
                                'minuteStep' => 10,
                            ],
                        ]) ?>
                </div>
                <div class="col-sm-6">
                    <i title="Время конца дня" class="glyphicon glyphicon-question-sign"></i>
                    <?= $form->field($model, 'time_to', ['options' => ['class' => 'form_group']])
                        ->widget(TimePicker::class, [
                            'pluginOptions' => [
                                'showSeconds' => false,
                                'defaultTime' => '19:00',
                                'showMeridian' => false,
                                'minuteStep' => 10,
                            ],
                        ]) ?>
                </div>
            </div>
            <i title="Рабочие дни" class="glyphicon glyphicon-question-sign"></i>
            <?= $form->field($model, 'working_days')->widget(Select2::class, [
                'data' => Company::$weekDays,
                'options' => [
                    'placeholder' => 'Дни работы',
                    'multiple' => true,
                    'value' => explode(',', $model->working_days),
                ],
                'pluginOptions' => [
                    'tags' => true,
                    'maximumInputLength' => 255,
                ],
            ])->label('Рабочие дни'); ?>
        </div>

        <div id="meta" class="tab-pane fade in">
            <?= MetaTags::widget([
                'model' => $model,
                'form' => $form
            ]); ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <div class="row">
        <div class="col-md-6">

        </div>
        <div class="col-md-6">
            <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => 'btn pull-right btn-success']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>


<?php if ($model->isNewRecord): ?>
    <script>
        $(document).ready(() => $('#company-name').syncTranslit({destination: 'company-url'}));
    </script>
<?php endif; ?>

<script>
    $('.open-url').on('click', () => {
        let message = confirm("Вы уверены, что хотите отредактировать это? Возможны негативные последствия в индексации!");
        if (message === true) {
            $('#company-url').removeAttr('disabled');
            $(document).ready(function () {
                $('#company-name').syncTranslit({destination: 'company-url'});
            });
            $('.open-url').attr('disabled', true);
        }
    });

    $(document).ready(() => initMap());
</script>


