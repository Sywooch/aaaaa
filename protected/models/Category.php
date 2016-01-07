<?php
namespace app\models;

use app\components\Convert;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

class Category extends ActiveRecord
{
    public static function tableName()
    {
        return 'categories';
    }

    public function rules()
    {
        return [
            [['id', 'parent_id'], 'number'],
            [['name', 'url'], 'string', 'max' => 255],
            [['name', 'url'], 'required'],
            [['parent_id'], 'default', 'value' => null],
        ];
    }

    public static function getBreadcrumbs($category_id = 0, $city = 'kazakhstan')
    {
        $index = $category_id;
        $data = [];
        if ($index != 0) {
            do {
                $model = self::findOne($index);
                if ($model) {
                    if ($category_id == $model->id) {
                        $data[] = ['label' => ($model->name)];
                    } else {
                        $data[] = [
                            'label' => ($model->name),
                            'url' => Url::toRoute("/" . $city . "/" . $model->id . $model->url),
                            'rel' => 'v:url',
                            'property' => 'v:title',
                            'itemprop' => 'title',
                        ];
                    }
                    $index = $model->parent_id;
                } else {
                    throw new Exception("Category not found!");
                }
            } while ($index > 0);
        }

        $breadcrumbs = [];
        for ($i = count($data) - 1; $i >= 0; $i--) {
            $breadcrumbs[] = $data[$i];
        }

        return $breadcrumbs;
    }

}