<?php

use common\models\Setting;
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
$copy = Setting::getValue('copy');
$phone = Setting::getValue('phone');
$email = Setting::getValue('email');

$face_phone = preg_replace(
    "/[^0-9]/", "",
    isset($phone) ? $phone : ''
);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>
  <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style type="text/css">
    @media only screen and (max-device-width: 767px) {
      .body {
        min-width: 300px !important;
      }
    }
  </style>
</head>
<body
    style="margin:0;padding:0;word-wrap: break-word; -webkit-nbsp-mode: space; -webkit-line-break: after-white-space;">
<div
    style="color: #4e2984;font-family:'Open Sans', sans-serif;background:#f6f7f7; padding-bottom: 50px;padding-top: 50px;">
  <center style="width:100%">
    <table class="body" style="max-width:600px;background-color:#fff;margin: 0 auto;" border="0" cellspacing="0"
           cellpadding="0">
      <tbody>
      <tr style="text-align: center;">
        <td>
          <img style="height: 40px;padding: 15px 0;"
               src="<?= Yii::$app->params['domainFrontend'] ?>/header-logo.png"
               alt=""
               height="60"/>
        </td>
      </tr>
      <?php $this->beginBody() ?>
      <?= $content ?>
      <?php $this->endBody() ?>
      <tr>
        <td style="color:#919f9f;font-size:13px;padding: 20px">
          <center style="width:100%">
            <div style="text-align: center; margin-bottom:10px"><?= strip_tags(@$copy ?: '') ?> <?= date('Y') ?></div>

            <div style=" margin-bottom: 20px;">
                          <span class="pe">
                          <strong>Телефон: </strong>
                              <a style="text-decoration: underline; color:#919f9f;"
                                 href="callto:+7<?= @$phone ? $face_phone : '' ?>"><?= @$phone ?: '' ?></a>
                          </span>
              <span class="pe">
                              <strong> E-mail: </strong>
                              <a style="text-decoration: underline; color:#919f9f;"
                                 href="mailto:<?= @$email ?: '' ?>"><?= @$email ?: '' ?></a>
                          </span>
              <style type="text/css">
                @media only screen and (max-device-width: 767px) {
                  .pe {
                    display: block !important;
                    margin-bottom: 10px !important;
                  }
              </style>
            </div>

            <div style="text-align: center;display:block;overflow:hidden; margin-bottom:20px">
              <div class="mobile-li" style="float: left; display: block; margin: 0; margin-left: 2em; padding: 5px">
                <a href="<?= Yii::$app->params['domainFrontend'] ?>/publications" style="color: #919f9f;">Публикации</a>
              </div>
              <div class="mobile-li" style="float: left; display: block; margin: 0; margin-left: 2em; padding: 5px">
                <a href="<?= Yii::$app->params['domainFrontend'] ?>/products" style="color: #919f9f;">Продукты</a>
              </div>
              <div class="mobile-li" style="float: left; display: block; margin: 0; margin-left: 2em; padding: 5px">
                <a href="<?= Yii::$app->params['domainFrontend'] ?>/about" style="color: #919f9f;">О проекте</a>
              </div>
              <div class="mobile-li" style="float: left; display: block; margin: 0; margin-left: 2em; padding: 5px">
                <a href="<?= Yii::$app->params['domainFrontend'] ?>/contacts" style="color: #919f9f;">Контакты</a>
              </div>
              <style type="text/css">
                @media only screen and (max-device-width: 767px) {
                  .mobile-li {
                    float: none !important;
                    margin-left: 0 !important;
                    text-align: center !important;
                  }

                  .mobile-li a {
                    text-decoration: none;
                  }
                }
              </style>
            </div>

            <div style="text-align:center;">
              <ul style="list-style:none; padding-left:0;">
                <li style="display:inline-block;">
                  <a title="Facebook" style="text-decoration:none" href="https://www.facebook.com/">
                    <img alt="Facebook" src="<?= Yii::$app->params['domainFrontend'] ?>/fb.png" width="30"/>
                  </a>
                </li>
                <li style="display:inline-block;">
                  <a title="Instagram" style="text-decoration:none" href="https://www.instagram.com/">
                    <img alt="Instagram" src="<?= Yii::$app->params['domainFrontend'] ?>/im.png" width="30"/>
                  </a>
                </li>
              </ul>
            </div>
          </center>
        </td>
      </tr>
      </tbody>
    </table>
  </center>
</div>
</body>
</html>
<?php $this->endPage() ?>
