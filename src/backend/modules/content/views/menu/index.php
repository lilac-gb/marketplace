<?php

use common\components\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Меню';
$this->params['breadcrumbs'][] = ['label' => 'Контент', 'url' => ['/content']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="menu-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'title',
            [
                'attribute' => 'levels',
                'value' => function ($data) {
                    return $data->levels ? "Да" : "Нет";
                },
            ],
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    return $data->status ? "Да" : "Нет";
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group btn-group-xs pull-right">{links} {update} {delete}</div>',
                'buttons' => [
                    'links' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-align-left"></span>', ['/content/menu-link/index', 'menu_id' => $model->id], [
                            'title' => 'Ссылки',
                            'data-pjax' => '0',
                            'class' => 'btn btn-default',
                        ]);
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
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'class' => 'btn btn-default',
                        ]);
                    }
                ],
                'contentOptions' => ['style' => 'min-width:165px;']
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0']
        ],
        'rowOptions' => function ($model, $index, $widget, $grid) {
            return [
                'class' => !$model->status ? 'danger' : '',

            ];
        },
        'tableOptions' => ['class' => 'table-hover itemColumn'],
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Создать', ['update'],
                    ['role' => 'modal-remote', 'title' => 'Создать', 'class' => 'btn btn-success modalForm'])
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
