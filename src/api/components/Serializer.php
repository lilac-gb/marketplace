<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 02.04.17
 * Time: 12:01
 */

namespace api\components;


use Yii;

class Serializer extends \yii\rest\Serializer
{
    /**
     * @var string the name of envelope
     * If this property is not set, the resource arrays will be directly returned without using metaTags envelope.
     */
    public $metaTagsEnvelope;
    public $metaTagsProvider;

    /**
     * @param \yii\data\ActiveDataProvider $dataProvider
     * @return array|null
     */
    protected function serializeDataProvider($dataProvider)
    {
        if ($this->preserveKeys) {
            $models = $dataProvider->getModels();
        } else {
            $models = array_values($dataProvider->getModels());
        }
        $models = $this->serializeModels($models);

        if (($pagination = $dataProvider->getPagination()) !== false) {
            $this->addPaginationHeaders($pagination);
        }

        if ($this->request->getIsHead()) {
            return null;
        } elseif ($this->collectionEnvelope === null) {
            return $models;
        } else {
            $result = [
                $this->collectionEnvelope => $models,
            ];

            if ($this->metaTagsEnvelope !== null) {
                $result = array_merge($result, $this->serializeMetaTags());
            }

            if ($pagination !== false) {
                return array_merge($result, $this->serializePagination($pagination));
            } else {
                return $result;
            }
        }
    }


    protected function serializeMetaTags()
    {
        if ($this->metaTagsEnvelope === null || $this->metaTagsProvider === null) {
            return [];
        }

        $metaTags = Yii::$app->controller->{$this->metaTagsProvider}();

        if (!$metaTags) {
            return [];
        }

        return [
            $this->metaTagsEnvelope => $metaTags
        ];
    }

    protected function serializeModelErrors($model)
    {
        $this->response->setStatusCode(422, 'Data Validation Failed.');

        return $model->firstErrors;
    }
}
