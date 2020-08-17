<?php

use backend\widgets\CKEditor;
use backend\widgets\MetaTags;
use common\components\ActiveForm;
use yii\helpers\Html;
use common\components\Tabs;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = $model->isNewRecord ? "Создание страницы" : 'Обновление страницы #' . $model->id;
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

            <i title="Сам материал, визуально может отличаться от того, что будет на странице"
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


<?php if ($model->isNewRecord): ?>
    <script>
        $(document).ready(() => $('#page-name').syncTranslit({destination: 'page-url'}));
    </script>
<?php endif; ?>

<script>
    $('.open-url').on('click', () => {
        let message = confirm("RU Вы уверены, что хотите отредактировать это? Возможны негативные последствия в индексации!");
        if (message === true) {
            $('#page-url').removeAttr('disabled');
            $(document).ready(function () {
                $('#page-name').syncTranslit({destination: 'news-url'});
            });
            $('.open-url').attr('disabled', true);
        }
    });
</script>

