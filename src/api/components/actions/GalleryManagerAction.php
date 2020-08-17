<?php

namespace api\components\actions;

use Imagine\Gd\Imagine;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\UploadedFile;
use zxbodya\yii2\galleryManager\GalleryBehavior;

class GalleryManagerAction extends \zxbodya\yii2\galleryManager\GalleryManagerAction
{
    public $pkGlue = '_';

    public $types = [];
    public $image;
    public $class = null;
    public $behaviorName;
    public $galleryId;
    protected $type;
    /** @var  ActiveRecord */
    protected $owner;
    /** @var  GalleryBehavior */
    protected $behavior;

    public function init()
    {
        if (Yii::$app->id == 'basic-console') {
            Yii::$app->set('request', ['class' => Request::class]);
            Yii::$app->set('response', ['class' => Response::class]);
        }
    }

    public function run($action)
    {
        $this->type = Yii::$app->request->get('type');
        $this->behaviorName = $this->behaviorName ?: Yii::$app->request->get('behaviorName');
        $this->galleryId = $this->galleryId ?: Yii::$app->request->get('galleryId');
        $pkNames = call_user_func([$this->class ?: $this->types[$this->type], 'primaryKey']);
        $pkValues = explode($this->pkGlue, $this->galleryId);

        $pk = array_combine($pkNames, $pkValues);

        $this->owner = call_user_func([$this->class ?: $this->types[$this->type], 'findOne'], $pk);
        $this->behavior = $this->owner->getBehavior($this->behaviorName);

        switch ($action) {
            case 'delete':
                return $this->actionDelete(Yii::$app->request->post('id'));
                break;
            case 'upload':
                return $this->actionUpload($this->image);
                break;
            case 'frontendUpload':
                return $this->actionFrontendUpload();
                break;
            case 'ajaxUpload':
                return $this->actionAjaxUpload();
                break;
            case 'changeData':
                return $this->actionChangeData(Yii::$app->request->post('photo'));
                break;
            case 'order':
                return $this->actionOrder(Yii::$app->request->post('order'));
                break;
            default:
                throw new HttpException(400, 'Action do not exists');
                break;
        }
    }

    protected function actionDelete($ids)
    {
        foreach ($ids as $id) {
            $db = Yii::$app->db;
            $db->createCommand()
                ->delete(
                    '{{%gallery_image}}',
                    ['id' => $id]
                )->execute();
        }

        $this->behavior->deleteImages($ids);

        return 'OK';
    }

    public function actionUpload($imageFile)
    {
        $test = new Imagine();
        try {
            $test->open($imageFile);
        } catch (\Exception $e) {
            return false;
        }
        $result = $this->behavior->addImage($imageFile);

        return $result;
    }

    public function actionFrontendUpload()
    {
        $imageFile = UploadedFile::getInstanceByName('file');

        ini_set('memory_limit', '-1');

        if ($imageFile->size > 2000000 || $imageFile->size == 0) {
            Yii::$app->response->setStatusCode(500, 'Изображение слишком большое! Максимальный размер 2мб!');
            Yii::$app->response->headers->set('Content-Type', 'text/html');

            return Json::encode([
                'message' => 'Изображение слишком большое! Максимальный размер 2мб!',
            ]);
        }

        if ($imageFile->size < 2000000 && $imageFile->size != 0) {
            $fileName = $imageFile->tempName;
            $image = $this->behavior->addImage($fileName);

            Yii::$app->response->headers->set('Content-Type', 'text/html');

            return Json::encode(
                [
                    'id' => $image->id,
                    'rank' => $image->rank,
                    'name' => (string)$image->name,
                    'description' => (string)$image->description,
                    'preview' => $image->getUrl('preview'),
                ]
            );
        }

        Yii::$app->response->setStatusCode(500, 'Ошибка!');

        return Json::encode(['message' => "Ошибка загрузки!"]);
    }

    public function actionAjaxUpload()
    {
        $imageFile = UploadedFile::getInstanceByName('gallery-image');

        ini_set('memory_limit', '-1');

        if ($imageFile->size > 2000000 || $imageFile->size == 0) {
            Yii::$app->response->setStatusCode(500, 'Изображение слишком большое! Максимальный размер 2мб!');
            Yii::$app->response->headers->set('Content-Type', 'text/html');

            return Json::encode([
                'message' => 'Изображение слишком большое! Максимальный размер 2мб!',
            ]);
        }

        /*if ($imageFile->size < 100000) {
            Yii::$app->response->setStatusCode(500, 'Изображение слишком маленькое! Минимальный размер 100кб!');
            Yii::$app->response->headers->set('Content-Type', 'text/html');
            return Json::encode([
                'message' => 'Изображение слишком маленькое или не корректное! Минимальный размер 100кб!'
            ]);
        }*/

        if (/*$imageFile->size > 100000 && */
            $imageFile->size < 2000000 && $imageFile->size != 0) {
            $fileName = $imageFile->tempName;
            $image = $this->behavior->addImage($fileName);

            // not "application/json", because  IE8 trying to save response as a file

            Yii::$app->response->headers->set('Content-Type', 'text/html');

            return Json::encode(
                [
                    'id' => $image->id,
                    'rank' => $image->rank,
                    'name' => (string)$image->name,
                    'description' => (string)$image->description,
                    'preview' => $image->getUrl('preview'),
                ]
            );
        }

        Yii::$app->response->setStatusCode(500, 'Ошибка!');

        return Json::encode(['message' => "Ошибка загрузки!"]);
    }

    public function actionChangeData($imagesData)
    {
        if (count($imagesData) == 0) {
            throw new HttpException(400, 'Nothing to save');
        }
        $images = $this->behavior->updateImagesData($imagesData);
        $resp = [];
        foreach ($images as $model) {
            $resp[] = [
                'id' => $model->id,
                'rank' => $model->rank,
                'name' => (string)$model->name,
                'description' => (string)$model->description,
                'preview' => $model->getUrl('preview'),
            ];
        }

        return Json::encode($resp);
    }

    public function actionOrder($order)
    {
        if (count($order) == 0) {
            throw new HttpException(400, 'No data, to save');
        }
        $res = $this->behavior->arrange($order);

        return Json::encode($res);
    }
}
