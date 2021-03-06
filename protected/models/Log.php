<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Log extends ActiveRecord
{
    public static function tableName()
    {
        return 'log';
    }

    public function rules()
    {
        return [
            [
                'source_id',
                'exist',
                'targetAttribute' => 'id',
                'targetClass' => Source::className(),
                'skipOnError' => true,
            ],
            [['updated'], 'date', 'format' => 'yyyy-M-d H:m:s'],
            [['source_id', 'updated'], 'required'],
        ];
    }

    public function getSource()
    {
        return $this->hasOne(Source::className(), ['id' => 'source_id']);
    }

    public function beforeValidate()
    {
        $this->updated = date('Y-m-d H:i:s');

        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }
}