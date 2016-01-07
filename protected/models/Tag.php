<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\validators\FilterValidator;

class Tag extends ActiveRecord
{
    public static function tableName()
    {
        return 'tag';
    }

    public function rules()
    {
        return [
            ['name', FilterValidator::className(), 'filter' => 'strip_tags'],
            ['name', 'string', 'max' => 255 ],
            ['name', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Тег',
        ];
    }

    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])
            ->viaTable('tag4post', ['tag_id' => 'id']);
    }

    public static function add($tag)
    {
        $model = self::findOne($tag) or self::findOne(['name' => $tag]);
        if (!$model) {
            $model = new self;
            $model->name = $tag;
            $model->save();
        }
        return $model;
    }


    /**
     * Минимальный размер шрифта
     */
    const MIN_FONT_SIZE = 1;

    /**
     * Максимальный размер шрифта
     */
    const MAX_FONT_SIZE = 10;

    public static function getTagWeights($limit = 20)
    {
        $models = Tag::find()->with('posts')->orderBy('name')->all();

        $minFrequency = 0;
        $maxFrequency = 0;
        foreach ($models as $model) {
            $weight = count($model->questions);
            $minFrequency = $minFrequency > $weight ? $weight : $minFrequency;
            $maxFrequency = $maxFrequency < $weight ? $weight : $maxFrequency;
        }


        $sizeRange = self::MAX_FONT_SIZE - self::MIN_FONT_SIZE;

        $minCount = log($minFrequency + 1);
        $maxCount = log($maxFrequency + 1);

        if ($maxCount != $minCount){
            $countRange = $maxCount - $minCount;
        } else {
            $countRange = 1;
        }

        $tags = [];
        foreach ($models as $model) {
            $tags[$model->name] = round(
                self::MIN_FONT_SIZE + (log(count($model->posts) + 1) - $minCount) * ($sizeRange / $countRange)
            );
        }

        asort($tags);

        return $tags;
    }

}