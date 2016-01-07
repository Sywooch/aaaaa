<?php
namespace app\models;

use yii\db\ActiveRecord;

class Answer extends ActiveRecord
{
    public static function tableName()
    {
        return 'answer';
    }

    public function rules()
    {
        return [
            [
                'question_id',
                'exist',
                'targetAttribute' => 'id',
                'targetClass' => Question::className(),
                'skipOnError' => true,
            ],
            [['author', 'ip', 'user_agent',], 'string', 'max' => 255],
            [['text'], 'string', 'min' => 4, 'max' => 65535],
            [['created'], 'date', 'format' => 'yyyy-M-d H:m:s'],
            [['visible'], 'in', 'range' => [0, 1]],
            [['visible'], 'default', 'value' => 0],
            [['author'], 'default', 'value' => 'Anonymous'],
            [['created'], 'default', 'value' => date("Y-m-d H:i:s")],
            [['question_id', 'text', 'author', 'created', 'visible', 'ip', 'user_agent',], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'author' => 'Автор',
            'text' => 'Ответ',
        ];
    }

    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }


}