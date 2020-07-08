<?php

use common\components\GridView;
use common\models\Ad;
use common\models\User;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Объявления';
$this->params['breadcrumbs'][] = $this->title;

$template = '{status} {update} {delete}';

?>
<div class="ad-index">
    <?php Pjax::begin(['id' => 'refresh']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => require(__DIR__ . '/_columns.php'),
        'tableOptions' => ['class' => 'table table-responsive table-hover itemColumn'],
        'rowOptions' => function ($model, $index, $widget, $grid) {
            switch ($model->status) {
                case Ad::STATUS_DELETED:
                    return ['class' => 'danger'];
                case Ad::STATUS_NOT_PUBLISHED:
                    return ['class' => 'default'];
                case Ad::STATUS_PUBLISHED:
                    return ['class' => 'success'];
                case Ad::STATUS_MODERATION:
                    return ['class' => 'warning'];
                case Ad::STATUS_TIME_END:
                    return ['class' => 'info'];
                default:
                    return '';
            }
        },
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['update'], ['class' => 'btn btn-success modalForm']),
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
