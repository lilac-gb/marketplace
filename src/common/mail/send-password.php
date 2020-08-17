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
        <p>
            Ваш логин: <b><?= $model->email ?></b>
        </p>
        <p>
            Ваш временный пароль: <b><?= $password ?></b>
        </p>
        <p style="color: red">
            Рекомендуем незамедлительно сменить его на свой в личном кабинете
        </p>
    </td>
</tr>
<tr>
    <td style="background-color: #4e2984;padding: 10px 15px 10px 15px;color: #fff;font-size: 77%;">
        <p style="text-align: center;">Данное письмо сгенерировано автоматически. Вы получили его на адрес
            <a style="color: #FFFFFF" href="<?= $model->email ?>"><?= $model->email ?></a>, так как
            кто-то инициировал процесс активации аккаунта. В случае, если Вы этого не делали,
            просто проигнорируйте это письмо.
        </p>
    </td>
</tr>
