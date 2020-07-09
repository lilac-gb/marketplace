<?php
/**
 * Created by PhpStorm.
 * User: rogozhuk
 * Date: 04.05.18
 * Time: 16:30
 */

use common\components\GridView;
use common\models\Ad;
use common\models\User;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Html;
use yii\helpers\Url;

return [
    'id',
    [
        'attribute' => 'user_id',
        'value' => function ($model) {
            /** @var $model Ad */
            return @$model->user ? $model->user->username : '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'data' => [
                '' => 'Все',
            ],
        ],
        'filter' => User::list(),
    ],
    [
        'label' => 'Раздел',
        'attribute' => 'Секция',
        'value' => function ($model) {
            /** @var $model Ad */
            return $model->section->name;
        },
        'filter' => false,
    ],
    [
        'label' => 'Тип',
        'attribute' => 'Тип',
        'value' => function ($model) {
            /** @var $model Ad */
            return $model->type->name;
        },
        'filter' => false,
    ],
    [
        'attribute' => 'name',
        'format' => 'raw',
        'value' => function ($model) {
            /** @var $model Ad */
            return
                Html::a(
                    $model->name,
                    $model->getUrl(),
                    [
                        'target' => '_blank',
                        'data-pjax' => 0,
                    ]
                );
        },
    ],
    [
        'attribute' => 'views',
        'options' => ['style' => 'width:25px;'],
    ],
    [
        'attribute' => 'created_at',
        'value' => function ($model) {
            /** @var $model Ad */
            return empty($model->created_at) ? null : $model->created_at;
        },
        'format' => 'date',
        'options' => ['style' => 'width:175px;'],
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'locale' => ['format' => 'YYYY-MM-DD'],
            ],
        ],
    ],
    [
        'attribute' => 'ended_at',
        'value' => function ($model) {
            /** @var $model Ad */
            return empty($model->ended_at) ? null : $model->ended_at;
        },
        'format' => 'date',
        'options' => ['style' => 'width:175px;'],
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'locale' => ['format' => 'YYYY-MM-DD'],
            ],
        ],
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '<div class="btn-group btn-group-xs pull-right">' . $template . '</div>',
        'buttons' => [
            'status' => function ($url, $model, $key) {
                /** @var Ad $model */
                $result = $model->status($model->status);
                $result .= Dropdown::widget([
                    'items' => [
                        [
                            'label' => '<i title="Редактирование" class="glyphicon glyphicon-pencil"></i>',
                            'url' => Url::to([
                                'set',
                                'id' => $model->id,
                                'attr' => 'status',
                                'val' => Ad::STATUS_NOT_PUBLISHED,
                            ]),

                        ],
                        [
                            'label' => '<i title="Разместить объявление" class="glyphicon glyphicon-ok"></i>',
                            'url' => Url::to([
                                'set',
                                'id' => $model->id,
                                'attr' => 'status',
                                'val' => Ad::STATUS_PUBLISHED,
                            ]),

                        ],
                        [
                            'label' => '<i  title="Модерация" class="glyphicon glyphicon-eye-open"></i>',
                            'url' => Url::to([
                                'set',
                                'id' => $model->id,
                                'attr' => 'status',
                                'val' => Ad::STATUS_MODERATION,
                            ]),

                        ],
                        [
                            'label' => '<i  title="Время закончено" class="glyphicon glyphicon-time"></i>',
                            'url' => Url::to([
                                'set',
                                'id' => $model->id,
                                'attr' => 'status',
                                'val' => Ad::STATUS_TIME_END,
                            ]),

                        ],
                    ],
                    'encodeLabels' => false,
                ]);

                return $result;
            },

            'update' => function ($url, $model, $key) {
                return Html::a('<i class="glyphicon glyphicon-pencil"></i>', $url, [
                    'title' => Yii::t('yii', 'Update'),
                    'data-pjax' => '1',
                    'class' => 'modalForm btn btn-default',
                ]);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a('<i class="glyphicon glyphicon-remove"></i>', $url, [
                    'title' => 'Полное удаление',
                    'data-confirm' => Yii::t('yii', 'Вы уверены что хотите окончательно удалить эту новость?'),
                    'data-method' => 'post',
                    'data-pjax' => '1',
                    'class' => 'btn btn-default',
                ]);
            },
        ],
        'contentOptions' => ['style' => 'min-width:400px;'],
    ],
];