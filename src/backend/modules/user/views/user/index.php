<?php

use common\models\News;
use yii\helpers\Html;
use common\components\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['/news']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            'id',
            'username',
            'email',
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">{status} {update} {delete}</div>',
                'buttons' => [
                    'status' => function ($url, $model, $key) {
                        if ($model->id === Yii::$app->user->id) {
                            return false;
                        }

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
