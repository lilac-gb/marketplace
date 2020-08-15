<?php
/** @var $model \common\models\Order */
?>

<tr style="text-align: center;">
  <td>
    <div style="
        background-image: url('<?= Yii::$app->params['domainFrontend'] ?>/images/mail/mail-fon.png');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        padding: 75px 0
        ">
    </div>
  </td>
</tr>
<tr>
    <td style="text-align: center; background-color: #ecf0f1;">
        <h3 style="text-transform:uppercase;margin:10px;padding:10px">
            <strong>Здравствуйте, <?= $model->name ?>!</strong>
        </h3>
    </td>
</tr>
<tr>
    <td style="padding:30px;">
        <h3>Мы получили от Вас новый заказ. В ближайшее время мы рассмотрим его</h3>
        <h4>Краткая информация:</h4>
        <b>Номер заявки:</b> <?= $model->id ?>
        <br><b>Дата публикации:</b> <?= date("d.m.Y H:i:s", $model->created_at) ?>
        <br><b>Email:</b> <?= $model->email ?>
        <br><b>Телефон:</b> <?= $model->phone ?>
        <br><b>Адрес:</b> <?= $model->address ?>
        <br><b>Примечания:</b> <?= $model->text ?>
    </td>
</tr>
<tr>
    <td style="background-color: #3d2654;padding: 10px 15px 10px 15px;color: #fff;font-size: 77%;">
        <p style="text-align: center;">Данное письмо сгенерировано автоматически.</p>
    </td>
</tr>