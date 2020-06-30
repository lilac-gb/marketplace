<?php
namespace backend\components;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $this->controllerNamespace = "backend\modules\\{$this->id}\\controllers";
        $this->defaultRoute = "{$this->id}/index";
    }
}
