<?php
/** @var $model \common\models\Order */
?>
<!--<tr style="text-align: center;">
    <td>
        <div style="
                background-image: url('<? /*= Yii::$app->params['domainFrontend'] */ ?>/img/mail/email-fon.jpg');
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                padding: 75px 0
                ">
        </div>
    </td>
</tr>-->
<tr>
    <td style="text-align: center; background-color: #ecf0f1;">
        <h3 style="text-transform:uppercase;margin:10px;padding:10px">
            <strong>Здравствуйте!</strong>
        </h3>
    </td>
</tr>
<tr>
    <td style="padding:30px;">
        <h3>Добавлена новая заявка</h3>
        <h4>Краткая информация:</h4>
        <b>Номер заявки:</b> <?= $model->id ?>
        <br><b>Дата публикации:</b> <?= date("d.m.Y H:i:s", $model->created_at) ?>
        <br><b>Пользователь:</b> <?= $model->name ?>
        <br><b>Email:</b> <?= $model->email ?>
        <br><b>Телефон:</b> <?= $model->phone ?>
        <br><b>IP:</b> <?= $model->ip ?>
        <br><b>Телефон:</b> <?= $model->phone ?>
        <br><b>Примичания:</b> <?= $model->text ?>
        <br><b>Адрес:</b> <?= $model->address ?>
    </td>
</tr>

<tr>
    <td style="background-color: #1f3f54;padding: 10px 15px 10px 15px;color: #fff;font-size: 77%;">
        <p style="text-align: center;">Данное письмо сгенерировано автоматически.</p>
    </td>
</tr>