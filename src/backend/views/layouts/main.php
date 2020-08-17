<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
use yii\widgets\Menu;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/img/favicon.ico?_7" type="image/x-icon" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-default navbar-fixed-top',
    ],
    'innerContainerOptions' => ['class' => 'container-fluid'],
]);

$menuItems = [
   /* [
        'template' => '<div class="menu-item">{label}</div>',
        'label' => 'Ваше время: <span class="time" id="hours">
                      <script src="/js/time.js"></script>
                       </span>  / Время сервера: ' . date('d.m.Y H:i'),
        'url' => false,
    ],*/
    [
        'label' => 'Вернуться на сайт',
        'url' => Yii::$app->params['domainFrontend'],
    ],
    [
        'label' => '<i class="glyphicon glyphicon-cog"></i> Настройки',
        'url' => ['/main/setting/index'],
        'visible' => Yii::$app->user->can('backend.main.setting'),
    ],
    [
        'label' => '<i class="on on-exit"></i>Выход',
        'url' => ['/main/main/logout'],
    ],
];

echo Menu::widget([
    'options' => ['class' => 'navbar-nav navbar-right nav'],
    'items' => $menuItems,
    'encodeLabels' => false,
]);
NavBar::end();
?>

<?= $content ?>

<?php Pjax::begin(['id' => 'refreshModal']); ?>

<?php
Modal::begin([
    'id' => 'modal',
    'options' => [
        'data-backdrop' => 'static',
        'tabindex' => false
    ],
    'size' => Yii::$app->controller->modalSize,
]);

echo "<div id='modalContent'></div>";

Modal::end();
?>

<?php Pjax::end();?>

<?php $this->endBody() ?>
<script>
    $(() => {
        $('#refresh').tooltip({
            selector: 'a, button, i'
        });
        $('body').tooltip({
            selector: 'i'
        });
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
