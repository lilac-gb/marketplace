<?php

use common\models\Order;
use common\models\User;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

return [
    [
        'attribute' => 'id',
        'options' => ['style' => 'width:80px;'],
    ],
    'name',
    'email',
    'phone',
    [
        'attribute' => 'user_id',
        'value' => 'user.fullName',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'data' => [
                '' => 'Все',
            ],
        ],
        'filter' => User::list(),
    ],
    [
        'attribute' => 'created_at',
        'value' => function (Order $model) {
            return empty($model->created_at) ? null : $model->created_at;
        },
        'format' => 'datetime',
        'options' => ['style' => 'min-width:200px;'],
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'locale' => ['format' => 'YYYY-MM-DD'],
            ],
        ],
    ],
    [
        'attribute' => 'status',
        'value' => function (Order $model) {
            return isset(Order::$statuses[$model->status]) ? Order::$statuses[$model->status] : null;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['multiple' => false],
        ],
        'filter' => Order::$statuses,
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '<div class="btn-group btn-group-xs pull-right">{update} {delete}</div>',
        'buttons' => [

            'update' => function ($url, $model, $key) {
                return Html::a('<i title="Редактировать" class="glyphicon glyphicon-pencil"></i>', $url, [
                    'title' => Yii::t('yii', 'Update'),
                    'data-pjax' => '1',
                    'class' => 'modalForm btn btn-default',
                ]);
            },

            'delete' => function ($url, $model, $key) {
                if ($model->status !== Order::STATUS_DELETED) {
                    return Html::a('<i title="Удалить" class="glyphicon glyphicon-trash"></i>', $url, [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '1',
                        'class' => 'btn btn-default',
                    ]);
                } else {
                    return Html::a('<span class="glyphicon glyphicon-share-alt"></span>',
                        Url::to([
                            'set',
                            'id' => $model->id,
                            'attr' => 'status',
                            'val' => Order::STATUS_PROCESS,
                        ]),
                        [
                            'title' => "Восстановить",
                            'class' => 'btn btn-default',
                        ]
                    );
                }
            },
        ],
        'contentOptions' => ['style' => 'min-width:140px;'],
    ],
];