<?php

use common\models\Order;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use common\components\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="page-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'id' => 'grid-orders',
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => require(__DIR__ . '/_columns.php'),
        'pjax' => true,
        'pager' => [
            'options' => ['class' => 'pagination pull-right', 'style' => 'margin-top:0'],
        ],
        'tableOptions' => ['class' => 'table-hover itemColumn'],
        'rowOptions' => function ($model, $index, $widget, $grid) {
            return [
                'class' =>
                    $model->status == Order::STATUS_DELETED ? 'danger' :
                        ($model->status == Order::STATUS_PROCESS ? 'warning' :
                            ($model->status == Order::STATUS_SHIPPING ? 'info' :
                                ($model->status == Order::STATUS_DONE ? 'success' : '')
                            )
                        ),
            ];
        },
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Создать заказ', ['update'], ['class' => 'btn btn-success modalForm']),
            ],
            '{pageSize}',
        ],
        'responsive' => true,
        'panel' => [
            'type' => 'default',
            'heading' => false,
            'after' => false,
        ],


        'summary' => false,
    ]); ?>
    <?php Pjax::end(); ?>
</div>