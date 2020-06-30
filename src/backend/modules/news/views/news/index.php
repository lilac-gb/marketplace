<?php

use common\models\News;
use common\models\User;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;
use common\components\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['/news']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    /** @var $model common\models\News */
                    return isset($model->user->username) ? $model->user->username : '';
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'id' => 'user_index',
                    'data' => [
                        '' => 'Все'
                    ]
                ],
                'filter' => User::list(),
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {
                    /** @var $model common\models\News */
                    return Html::a($model->name, Yii::$app->params['domainFrontend'] . $model->getUrl(), ['target' => '_blank']);
                }
            ],
            [
                'attribute' => 'published_at',
                'options' => ['style' => 'min-width:200px;'],
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'locale' => ['format' => 'YYYY-MM-DD'],
                    ],
                ],
                'value' => function ($model) {
                    /** @var $model News*/
                    return $model->published_at == 0 ? '' : date('d.m.Y H:i', $model->published_at);
                }
            ],
            [
                'attribute' => 'created_at',
                'options' => ['style' => 'min-width:200px;'],
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'locale' => ['format' => 'YYYY-MM-DD'],
                    ],
                ],
                'value' => function ($model) {
                     /** @var $model News*/
                     return $model->created_at == 0 ? '' : date('d.m.Y H:i', $model->created_at);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">{status} {update} {delete}</div>',
                'buttons' => [
                    'status' => function ($url, $model, $key) {
                        /** @var News $model*/
                        $result = $model->status($model->status);
                        $result .= Dropdown::widget([
                            'items' => [
                                [
                                    'label' => '<i title="Скрыто" class="glyphicon glyphicon-remove"></i>',
                                    'url' => Url::to([
                                        'set',
                                        'id' => $model->id,
                                        'attr' => 'status',
                                        'val' => News::STATUS_DELETED,
                                    ]),

                                ],
                                [
                                    'label' => '<i title="Скрыто" class="glyphicon glyphicon-pencil"></i>',
                                    'url' => Url::to([
                                        'set',
                                        'id' => $model->id,
                                        'attr' => 'status',
                                        'val' => News::STATUS_NOT_PUBLISHED,
                                    ]),

                                ],
                                [
                                    'label' => '<i title="Разместить" class="glyphicon glyphicon-ok"></i>',
                                    'url' => Url::to([
                                        'set',
                                        'id' => $model->id,
                                        'attr' => 'status',
                                        'val' => News::STATUS_PUBLISHED,
                                    ]),

                                ],
                                [
                                    'label' => '<i  title="Модерация" class="glyphicon glyphicon-eye-open"></i>',
                                    'url' => Url::to([
                                        'set',
                                        'id' => $model->id,
                                        'attr' => 'status',
                                        'val' => News::STATUS_MODERATION,
                                    ]),

                                ],
                            ],
                            'encodeLabels' => false,
                        ]);

                        return $result;
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '1',
                            'class' => 'modalForm btn btn-default',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Ds уверены, что хотите удалить это?'),
                            'data-method' => 'post',
                            'data-pjax' => '1',
                            'class' => 'btn btn-default',
                        ]);
                    }
                ],
                'contentOptions' => ['style' => 'min-width:200px;']
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
        ],
        'rowOptions' => function ($model, $index, $widget, $grid) {
            return [
                'class' => $model->status == News::STATUS_DELETED ? 'danger' : '',
            ];
        },
        'tableOptions' => ['class' => 'table-hover itemColumn'],
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Создать', ['update'],
                    ['role'=>'modal-remote','title'=> 'Создать','class'=>'btn btn-success modalForm'])
            ],
            '{pageSize}',
        ],
        'responsive' => true,
        'panel' => [
            'type' => 'default',
            'heading' => false,
            //'before'=>'<em>'.Yii::t('rbac','* Resize table columns just like a spreadsheet by dragging the column edges.').'</em>',
            'after' => false,
        ],
        'summary' => false,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
