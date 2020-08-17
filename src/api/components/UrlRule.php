<?php

namespace api\components;

class UrlRule extends \yii\rest\UrlRule {
    public $extraTokens = [];

    public function init()
    {
        $this->tokens = $this->tokens + $this->extraTokens;

        parent::init();
    }
}
