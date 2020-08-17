<?php

namespace common\models;

use common\components\ActiveRecord;
use v0lume\yii2\metaTags\MetaTagBehavior;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the model class for table "ads_types".
 *
 * @property integer $id
 * @property integer $status
 * @property string  $name
 * @property string  $url
 */
class AdType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'freq'], 'integer'],
            [['icon'], 'string'],
            [['name'], 'string', 'max' => 200],
            [['url'], 'string', 'max' => 250],
            [['freq'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'icon' => 'Иконка',
            'url' => 'URL',
            'status' => 'Статус',
        ];
    }

    public function behaviors()
    {
        return [
            'MetaTag' => [
                'class' => MetaTagBehavior::class,
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'url',
                'uniqueSlugGenerator' => '-',
            ],
        ];
    }

    public static function list($prompt = false)
    {
        $result = [];

        if ($prompt)
            $result[0] = $prompt;

        $ads = AdType::find()->all();

        foreach ($ads as $ad)
            $result[$ad->id] = $ad->name;

        return $result;
    }

    public function search($params = null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize',
                    Yii::$app->cache->get(self::class . '_pageSize') ?
                        Yii::$app->cache->get(self::class . '_pageSize') : 10),
            ],
        ]);

        return $dataProvider;
    }
}
