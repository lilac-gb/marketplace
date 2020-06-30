<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;


/* @var $this \yii\web\View */
/* @var $content string */

//\kartik\spinner\SpinnerAsset::register($this);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="text-center">
    <div style="display: block; max-width: 700px; margin: 0 auto;">
        <div class="col-md-12"><?= $content ?></div>
        <br/><br/><br/><br/><br/><br/>
        <a href="https://office.devcollector.art/" class="btn btn-success col-md-12">Перейти на главную</a>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
