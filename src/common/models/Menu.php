<?php

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\caching\DbDependency;
use yii\helpers\Url;
use Yii;


/**
 * This is the model class for table "menus".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $levels
 * @property integer $status
 * @property integer $updated_at
 */
class Menu extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'title', 'levels', 'status'], 'required'],
            [['levels', 'status', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['title'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'name' => 'Системное название',
            'title' => 'Описание',
            'levels' => 'Двухуровневое',
            'status' => 'Отображать',
            'updated_at' => 'Последнее обновление',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
            ],
        ];
    }

    public function getItems()
    {
        return $this->hasMany(MenuLink::class, ['menu_id' => 'id'])->where(['parent_id' => 0])->orderBy('order ASC');
    }

    public function getItemsArray()
    {
        $items = [];

        $dependency = new DbDependency([
            'sql' => 'SELECT max(updated_at) FROM menus_links WHERE menu_id = :menu_id AND parent_id = 0',
            'params' => [':menu_id' => $this->id],
        ]);

        $rawItems = $this::getDb()->cache(function() {
            return $this->items;
        }, 0, $dependency);


        foreach($rawItems as $item) {
            $children = [];
            $activeChild = false;

            if ($this->levels) {
                foreach ($item->children as $child) {
                    if (Url::to() == $child->url || strpos(Url::to(), $child->url) !== false)
                        $activeChild = true;

                    $children[] = [
                        'id' => $item->id ? : '',
                        'order' => $item->order ? : '',
                        'label' => $child->title ? : '',
                        'url' => $child->url ? : '',
//                        'domain' => $item->domain ? : '',
                        'linkOptions' => ['class' => $child->class ? : '',],
                        'active' => (Url::to() == $child->url || strpos(Url::to(), $child->url) !== false),
                        'visible' => $child->status,
                    ];
                }
            }

            $items[] = [
                'id' => $item->id ? : '',
                'order' => $item->order ? : '',
                'label' => $item->title ? : '',
                'url' => $item->url ? : '',
//                'domain' => $item->domain ? : '',
                'linkOptions' => ['class' => $item->class ? : '',],
                'options' => ['class' => empty($children) ? 'no-children' : 'dropdown'],
                'active' => (Url::to() == $item->url || strpos(Url::to(), $item->url) !== false) || $activeChild,
                'visible' => $item->status,
                'items' => $children ? : null,
            ];
        }

        return $items;
    }

    public function search($params=null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['status' => $this->status]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' =>
                    Yii::$app->request->get('pageSize',
                        Yii::$app->cache->get(self::class . '_pageSize') ?
                            Yii::$app->cache->get(self::class . '_pageSize') : 10),
            ]
        ]);

        return $dataProvider;
    }
}
