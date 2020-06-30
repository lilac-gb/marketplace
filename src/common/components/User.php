<?php
namespace common\components;

use common\models\Dialog;
use common\models\Message;
use Yii;

class User extends \yii\web\User
{
    /**
     * @var \common\models\User;
     */
    private $_model = null;

    public function getModel()
    {
        if (!$this->isGuest && !isset($this->_model)) {
            $user = new $this->identityClass();

            $this->_model = $user::findOne( $this->id );
        }

        return $this->_model;
    }

    public function getRoles() {
        return [];
    }

    public function getUsername()
    {
        return $this->getModel() ? $this->getModel()->name : '';
    }

    public function getStatus()
    {
        return $this->getModel() ? $this->getModel()->status : \common\models\User::STATUS_INACTIVE;
    }

    public function setRate($entity, $id, $rate)
    {
        if ($this->isGuest) {
            return false;
        }

        $model = $this->getModel();

        $rates = unserialize($model->rates);

        if (!isset($rates[$entity][$id])) {
            $rates[$entity][$id] = $rate;
        }

        $model->rates = serialize($rates);
        $model->updateAttributes(['rates']);

        return true;
    }

    /**
     * @return string
     */

    public function hasRate($entity, $id)
    {
        if ($this->isGuest) {
            return false;
        }

        $model = $this->getModel();

        $rates = unserialize($model->rates);

        return isset($rates[$entity][$id]);
    }

    public function getRate($entity, $id)
    {
        if ($this->isGuest) {
            return null;
        }

        $model = $this->getModel();

        $rates = unserialize($model->rates);

        return @$rates[$entity][$id] ?: false;
    }

    public function superUser(){
        $superUser = array_key_exists(\common\models\User::ROLE_SUPPORT, Yii::$app->user->getRoles())
            || array_key_exists(\common\models\User::ROLE_ADMIN, Yii::$app->user->getRoles());
        return $superUser;
    }

    public function userSupport(){
        $support = array_key_exists(\common\models\User::ROLE_SUPPORT, Yii::$app->user->getRoles());
        return $support;
    }

    /**
     * @param string $permissionName constructed permission name (app.module.controller.action)
     * @param array $params
     * @param bool|true $allowCaching
     * @return bool
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        return true;

        $auth = Yii::$app->authManager;

        //1 level
        if ($auth->getPermission($permissionName) !== null) {
            return parent::can($permissionName, $params, $allowCaching);
        }

        //next levels
        while (strpos($permissionName, '.')) {
            //cut off last namespace
            $pos = strlen($permissionName) - strpos(strrev($permissionName), '.');
            $permissionName = substr($permissionName, 0, --$pos);

            if ($auth->getPermission($permissionName) !== null) {
                return parent::can($permissionName, $params, $allowCaching);
            }
        }

        return false;
    }

    public function getSuper() {
        return true;
    }
}
