<?php

use common\components\GridView;
use common\models\Company;
use common\models\User;
use yii\widgets\Pjax;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Компании';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'owner_id',
                'value' => function ($model) {
                    return isset($model->user->username) ? $model->user->username : '';
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'data' => [
                        '' => 'Все'
                    ]
                ],
                'filter' => User::list(),
            ],
            'name',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function($model) {
                    /** @var $model common\models\Company */
                    return Html::a($model->name, Yii::$app->params['domainFrontend'] . $model->getUrl(), ['target' => '_blank']);
                }
            ],
            [
                'attribute' => 'working_days',
                'value' => function ($model) {
                    $days = [];
                    /** @var $model common\models\Company */
                    $arrDays = explode(',', $model->working_days);
                    foreach ($arrDays as $day) {
                        $days[] = Company::$weekDays[(int)$day];
                    }
                    return implode(",", $days);
                },
            ],
            [
                'attribute' => 'time_from',
                'value' => function ($model) {
                    return $model->time_from;
                },
            ],
            [
                'attribute' => 'time_to',
                'value' => function ($model) {
                    return $model->time_to;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">{update} {delete}</div>',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '1',
                            'class' => 'modalForm btn btn-default',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        if (!Yii::$app->user->getSuper()) {
                            return '';
                        }

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
