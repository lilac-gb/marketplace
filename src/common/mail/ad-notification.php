<?php
/* @var $ad \common\models\Ad */
/* @var $link  string */
/* @var $user  \common\models\User */
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
            <strong>Здравствуйте!</strong>
        </h3>
    </td>
</tr>
<tr>
    <td style="padding:30px;">
        <h4>Краткая информация по объявлению:</h4>
        <b>Идентификационный номер объявления:</b> - <?= $ad->id ?>
        <br><b>Дата обновления создания:</b> <?= date("d.m.Y H:i:s", $ad->time_create) ?>
        <br><b>Пользователь:</b> <a title="URL пользователя"
                                    href="<?= Yii::$app->params['domainFrontend'] ?>/users/<?= $user->id ?>"><?= $user->getFullName() ?></a>
        <br><b>Email пользователя:</b> <?= $user->email ?>
        <br><b>Название:</b> <?= $ad->name ?>
        <br><b>Текст:</b> <?= $ad->description ?>
    </td>
</tr>
<tr>
    <td style="padding:30px;">
        <p>Объявление отправлено на модерацию. Для просмотра перейдите по следующей <a href="<?= $link ?>">ссылке</a>
        </p>
    </td>
</tr>
<tr>
    <td style="background-color: #1f3f54;padding: 10px 15px 10px 15px;color: #fff;font-size: 77%;">
        <p style="text-align: center;">Данное письмо сгенерировано автоматически. Вы получили его так
            как <?= $user->getFullName() ?> отправил объявление на модерацию.
        </p>
    </td>
</tr>