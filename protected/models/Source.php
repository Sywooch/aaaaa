<?php
namespace app\models;

use yii\db\ActiveRecord;

class Source extends ActiveRecord
{
    public static function tableName()
    {
        return 'source';
    }

    public function rules()
    {
        return [
            [['url', 'parser'], 'string', 'max' => 255],
            ['enable', 'boolean'],
            [['url', 'enable'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'url' => 'URL',
            'parser' => 'Класс парсера',
            'enable' => 'Включено',
        ];
    }

    public function getLogs()
    {
        return $this->hasOne(Log::className(), ['source_id' => 'id']);
    }

    public function updateLog()
    {
        $model = Log::findOne($this->id);
        if(!$model){
            $model = new Log();
            $model->source_id = $this->id;
        }
        $model->updated = date("Y-m-d H:i:s");

        return $model->save();
    }

}