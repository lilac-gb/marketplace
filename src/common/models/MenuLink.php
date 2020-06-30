<?php

namespace common\models;

use common\components\ActiveRecord;
use himiklab\sortablegrid\SortableGridBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menus_links".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property integer $parent_id
 * @property string $title
 * @property string $url
 * @property string $class
 * @property integer $order
 * @property integer $status
 * @property integer $updated_at
 */
class MenuLink extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menus_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'url', 'status'], 'required'],
            [['menu_id', 'parent_id', 'order', 'status', 'updated_at', 'created_at'], 'integer'],
            [['title'], 'string', 'max' => 550],
            [['url'], 'string', 'max' => 200],
            [['class'], 'string', 'max' => 250],
            ['parent_id', 'default', 'value' => 0],
            ['status', 'default', 'value' => 1],
            [['title'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'menu_id' => 'Меню',
            'parent_id' => 'Родитель',
            'title' => 'Название',
            'url' => 'Url',
            'class' => 'Дополнительные классы',
            'order' => 'Порядок',
            'status' => 'Отображать',
        ];
    }


    public function behaviors()
    {
        return [
            'sort' => [
                'class' => SortableGridBehavior::class,
                'sortableAttribute' => 'order'
            ],
            [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    public function getMenu() {
        return $this->hasOne(Menu::class, ['id' => 'menu_id']);
    }

    public function getParent()
    {
        return $this->hasOne(MenuLink::class, ['id' => 'parent_id']);
    }

    public function getChildren()
    {
        return $this->hasMany(MenuLink::class, ['parent_id' => 'id'])->orderBy('order ASC');
    }

    public static function getParents($menu_id, $exclude_id=0)
    {
        $links = MenuLink::find()->where(['menu_id' => $menu_id, 'parent_id' => 0])->andWhere(['not', ['id' => $exclude_id]])->all();

        $result = ArrayHelper::map($links, 'id', 'title');
        $result = array_map('strip_tags', $result);

        return $result;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->menu) {
            $this->menu->touch('updated_at');
        }

        parent::afterSave($insert, $changedAttributes);
    }

}
