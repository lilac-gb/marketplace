<?php

namespace api\components\actions;

use Yii;
use yii\data\ActiveDataProvider;

class IndexAction extends \yii\rest\Action
{
    public $prepareDataProvider;

    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider();
    }

    protected function prepareDataProvider()
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;
        $modelClass = new $modelClass();

        return method_exists($modelClass, 'search')
            ? $modelClass->search(Yii::$app->request->get(), '')
            : Yii::createObject([
                'class' => ActiveDataProvider::class,
                'query' => $modelClass::find(),
            ]);
    }
}
