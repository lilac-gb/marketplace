<?php
/**
 * Created by PhpStorm.
 * User: rogozhuk
 * Date: 19.07.20
 * Time: 10:42
 */

namespace console\controllers;


use common\models\Setting;

class SettingsController extends \yii\console\Controller
{
    public function actionGenerate()
    {
        $data = [
            [
                'section_id' => Setting::TYPE_EMAIL,
                'code' => 'email',
                'name' => 'Email везде',
                'entity' => 'feedback@vbs.one',
                'input_type' => 'text',
                'status' => Setting::STATUS_PUBLISHED,
            ],
            [
                'section_id' => Setting::TYPE_NAMES,
                'code' => 'phone',
                'name' => 'Телефон везде',
                'entity' => '+7(910)-448-11-18',
                'input_type' => 'text',
                'status' => Setting::STATUS_PUBLISHED,
            ],
            [
                'section_id' => Setting::TYPE_NAMES,
                'code' => 'copy',
                'name' => 'Копирайт в письме',
                'entity' => '© Copyright MarketPlace',
                'input_type' => 'text',
                'status' => Setting::STATUS_PUBLISHED,
            ],
        ];

        foreach ($data as $item) {
            $model = new \common\models\Setting();
            $model->section_id = $item['section_id'];
            $model->code = $item['code'];
            $model->name = $item['name'];
            $model->entity = $item['entity'];
            $model->input_type = $item['input_type'];
            $model->status = $item['status'];
            $model->save();
        }
    }
}