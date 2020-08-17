<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;


?>
<div class="info-404-text col-md-12">
    <h1><?= Html::encode($this->title) ?></h1>
    К сожалению что-то пошло не так! <?= nl2br(Html::encode($message)) ?>.
</div>



