<?php

use yii\helpers\Html;
use common\components\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разделы объявлений';
$this->params['breadcrumbs'][] = ['label' => 'Разделы', 'url' => ['/section']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'pjax' => true,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width:65px;'],
            ],
            'url',
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">{status} {update} {delete}</div>',
                'buttons' => [
                    'status' => function ($url, $model, $key) {
                        return Html::a(
                            '<i class="glyphicon glyphicon-eye-' . ($model->status ? 'open' : 'close') . '" title="Опубликовано"></i>',
                            Url::to([
                                'set',
                                'id' => $model->id,
                                'attr' => 'status',
                                'val' => $model->status ? 0 : 1,
                            ]),
                            [
                                'class' => $model->status == 1 ? 'btn btn-success' : 'btn btn-danger',
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
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '1',
                            'class' => 'btn btn-default',
                        ]);
                    },
                ],
                'contentOptions' => ['style' => 'min-width:125px;'],
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0'],
        ],
        'tableOptions' => ['class' => 'table-hover itemColumn'],
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Создать', ['update'],
                    ['role' => 'modal-remote', 'title' => 'Создать', 'class' => 'btn btn-success modalForm']) .
                Html::a('<i title="Обновить таблицу" class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Обновить']),
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
