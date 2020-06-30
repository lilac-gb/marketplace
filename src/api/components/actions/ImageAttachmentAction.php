<?php

namespace api\components\actions;

use Yii;

class ImageAttachmentAction extends \zxbodya\yii2\imageAttachment\ImageAttachmentAction
{
    public function run($type, $behavior, $id)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return parent::run($type, $behavior, Yii::$app->user->id);
    }
}
