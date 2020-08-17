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

    К сожалению у Вас нет доступа к этому разделу! Войдите или зарегистрируйтесь. В случае, если Вы уверены, что такого
    не должно быть, обратитесь в ТехПоддержку.

    <?= nl2br(Html::encode($message)) ?>

</div>
