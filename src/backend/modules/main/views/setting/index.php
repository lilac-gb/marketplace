<?php

use common\components\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\models\Setting;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['/main/setting']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            'id',
            [
                'attribute' => 'section_id',
                'value' => function ($model) {
                    switch ($model->section_id) {
                        case Setting::TYPE_EMAIL:
                            return '<i title="Email" class="ic ic-envelope"></i>';
                        case Setting::TYPE_NAMES:
                            return '<i title="Названия" class="ic ic-pencil"></i>';
                        case Setting::TYPE_STATISTIC:
                            return '<i title="Счетчики" class="ic ic-statistic"></i>';
                        case Setting::TYPE_KEYS:
                            return '<i title="Ключи" class="ic ic-keys"></i>';
                    }

                    return null;
                },
                'format' => 'raw',
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'data' => [
                        '' => 'Все'
                    ]
                ],
                'filter' => Setting::$settingsTypes
            ],
            'code',
            'name',
            [
                'attribute' => 'entity',
                'value' => function($model){
                    /** @var $model \common\models\Setting*/
                    return ($model->input_type == 'editor' || $model->input_type == 'textarea') ? 'Большие данные' : $model->entity;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">{status}{update}{delete}</div>',
                'buttons' => [
                    'status' => function ($url, $model, $key) {
                        return Html::a(
                            '<i title="Статус" class="glyphicon glyphicon-eye-'. ($model->status ? 'open' : 'close') .'"></i>',
                            Url::to([
                                'set',
                                'id' => $model->id,
                                'attr' => 'status',
                                'val' => $model->status ? 0 : 1,
                            ]),
                            [
                                'class' => 'btn '.($model->status ? 'btn-success' : 'btn-danger'),
                            ]
                        );
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                            'class' => 'modalForm btn btn-default',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Вы уверены, что хотите удалить это? При не верном удалении настройки, это может повлиять на работоспособность ресурса'),
                            'data-method' => 'post',
                            'data-pjax' => '1',
                            'class' => 'btn btn-default',
                        ]);
                    }
                ],
                'contentOptions' => ['style' => 'min-width:135px;']
            ],
        ],
        'tableOptions' => ['class' => 'table table-responsive table-hover itemColumn'],
        'rowOptions' => function ($model, $index, $widget, $grid){
            return ['class' => $model->status ? '' : 'danger'];
        },
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['update'], ['class' => 'btn btn-success modalForm'])
            ],
            '{pageSize}',
        ],
        'responsive' => true,
        'panel' => [
            'type' => 'default',
            'heading' => false,
            //'before'=>'<h4>Регион</h4>',
            'after' => false,
        ],
        'summary' => false,
    ]); ?>
    <?php Pjax::end(); ?>

</div>
