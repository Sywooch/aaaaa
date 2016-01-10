<?php
namespace app\models;

use yii\db\ActiveRecord;

class Vote extends ActiveRecord
{
    public static function tableName()
    {
        return 'vote';
    }

    public function rules()
    {
        return [
            [
                'post_id',
                'exist',
                'targetAttribute' => 'id',
                'targetClass' => Post::className(),
                'skipOnError' => true,
            ],
            [['ip', 'user_agent',], 'string', 'max' => 255],
            [['created'], 'date', 'format' => 'yyyy-M-d H:m:s'],
//            ['post_id, ip, user_agent', 'unique'],
            [['rating'], 'default', 'value' => 0],
            [['created'], 'default', 'value' => date("Y-m-d H:i:s")],
            [['post_id', 'rating', 'ip', 'user_agent', 'created',], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'post_id' => 'Пост',
            'rating' => 'Оценка',
        ];
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
}