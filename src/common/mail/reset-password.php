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
            Для смены Вашего пароля в личном кабинете, нажмите Восстановить пароль.
        </p>
        <div style="width: 100%;text-align: center;margin-top: 25px;margin-bottom: 25px">
            <a style="
        padding: 7px 10px;
        font-size: 14px;
    text-transform: uppercase;
    text-decoration: none;
    border-radius: 4px;
    background-color: #6b2cad;
    border: 1px solid #4e2984;
    color: #FFFFFF;"
               href="<?= $link; ?>">
                Восстановить пароль
            </a>
        </div>
    </td>
</tr>
<tr>
    <td style="background-color:  #4e2984;padding: 10px 15px 10px 15px;color: #fff;font-size: 77%;">
        <p style="text-align: center;">Данное письмо сгенерировано автоматически. Вы получили его на адрес
            <a style="color: #FFFFFF" href="<?= $model->email ?>"><?= $model->email ?></a>, так как
            кто-то инициировал процесс восстановления пароля. В случае, если Вы этого не делали,
            просто проигнорируйте это письмо.
        </p>
    </td>
</tr>
