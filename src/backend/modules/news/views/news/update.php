<?php

use backend\widgets\CKEditor;
// use backend\widgets\MetaTags;

use backend\widgets\GalleryManager;
use backend\widgets\MetaTags;
use common\components\ActiveForm;
use common\models\User;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use common\components\Tabs;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->isNewRecord ? "Создание новости" : 'Обновление новости #' . $model->id;
?>

<h4><?= Html::encode($this->title) ?></h4>

<script src="<?= Yii::$app->params['urlToSyncTranslit'] ?>"></script>
<?php $form = ActiveForm::begin([
    'id' => 'news-form',
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
            <div class="photo-block">
                <?php echo $model->id > 0 ? GalleryManager::widget([
                    'model' => $model,
                    'behaviorName' => 'galleryBehavior',
                    'apiRoute' => 'news/galleryApi',
                ]) : '<i title="Так как материалу не присвоен ID галлерею нельзя загрузить" class="glyphicon glyphicon-question-sign"></i> 
                           <b>Фото можно загрузить после сохранения материала</b><br><br>'; ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <i title="Время автоматической публикации новости" class="glyphicon glyphicon-question-sign"></i>
                    <?= $form->field($model, 'published_at', ['options' => ['class' => 'form_group']])
                        ->widget(DateTimePicker::class, [
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy hh:ii',
                            ],
                        ]) ?>
                </div>
                <div class="col-md-6">
                    <i title="Время отображаемое в новости" class="glyphicon glyphicon-question-sign"></i>
                    <?= $form->field($model, 'created_at', ['options' => ['class' => 'form_group']])
                        ->widget(DateTimePicker::class, [
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy hh:ii',
                            ],
                        ]) ?>
                </div>
            </div>

            <?= $form->field($model, 'user_id')->widget(Select2::class, [
                'id' => 'user-update',
                'data' => [
                    NULL => 'Без автора',
                    'Автор' => User::list()
                ],
                'options' => [
                    'id' => 'user_id',
                ],
            ])->hint('Выберите владельца') ?>

            <i title="Название материала на странице" class="glyphicon glyphicon-question-sign"></i>
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

            <i title="Анонс материала" class="glyphicon glyphicon-question-sign"></i>
            <?= $form->field($model, 'anons')->textarea() ?>

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
                    'onText' => 'Видено',
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


<?php if ($model->isNewRecord): ?>
    <script>
        $(document).ready(() => $('#news-name').syncTranslit({destination: 'news-url'}));
    </script>
<?php endif; ?>

<script>
    toggleContentType = data => {
        let val = parseInt(data.target.value);
        let user = $('.field-user_id');
        let tags = $('.field-news-tags');
        if (val === 1) {
            user.addClass('hidden');
            tags.addClass('hidden')
        }
        if (val === 2) {
            user.removeClass('hidden');
            tags.removeClass('hidden')
        }
    };
    $('.open-url').on('click', () => {
        let message = confirm("RU Вы уверены, что хотите отредактировать это? Возможны негативные последствия в индексации!");
        if (message === true) {
            $('#news-url').removeAttr('disabled');
            $(document).ready(function () {
                $('#news-name').syncTranslit({destination: 'news-url'});
            });
            $('.open-url').attr('disabled', true);
        }
    });
</script>
