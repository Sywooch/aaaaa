<?php
namespace app\components;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\Exception;

class ContentGenerator
{
    const TYPE_IMAGE = "image";
    const TYPE_VIDEO = "video";
    const TYPE_TEXT = "text";
    const TYPE_LINK = "link";

    public static function Parse($data)
    {
        $output = "";

        if (is_array($data)) {
            foreach ($data as $record) {
                $output .= self::Parse($record);
            }
        }

        if (!isset($data->type)){
            throw new Exception("Type is not defined!");
        }

        switch ($data->type) {
            case self::TYPE_TEXT:
                $output = Html::encode($data->src);
                break;

            case self::TYPE_IMAGE:
                $output = Html::img(Html::encode($data->src), [
                        'alt' => Html::encode($data->alt),
                        'class' => 'img-responsive center-block'
                    ]);
                break;

            case self::TYPE_VIDEO:
                $output = Html::tag(
                        "iframe",
                        null,
                        [
                            'src' => Html::encode($data->src),
                            'class' => 'embed-responsive-item',
                            'allowfullscreen' => true,
                            'style' => ['border' => 'none'],
                            'height' => '400',
                            'width' => '100%',
                        ]
                    );
                break;

            case self::TYPE_LINK:
                $output = Html::a(
                        isset($data->title) ? $data->title : $data->src,
                        Html::encode($data->src)
                    );
                break;

            default:
                throw new Exception("Type is not supported!");
        }

        return
            Html::tag(
                "h3",
                Html::encode(ArrayHelper::getValue($data, 'title')),
                ['class' => 'text-center']
            ) .
            $output .
            Html::tag(
                "p",
                Html::encode(ArrayHelper::getValue($data, 'description'))
            );
    }

}