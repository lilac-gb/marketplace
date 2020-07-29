<?php

namespace api\components\actions;

use \zxbodya\yii2\imageAttachment\ImageAttachmentAction as ImageAction;
use Yii;

class ImageAttachmentAction extends ImageAction
{
    public function run($type, $behavior, $id = 0)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return parent::run($type, $behavior, Yii::$app->user->id);
    }
}
