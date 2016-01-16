<?php
namespace app\components;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\Exception;

class ContentGenerator
{
    const TYPE_IMAGE = "image";
    const TYPE_VIDEO = "video";
    const TYPE_EMBED = "embed";
    const TYPE_LINK = "link";

    public static function Parse($data)
    {
        $output = "-+-";

        if (!isset($data->type)){
            throw new Exception("Type is not defined!");
        }

        switch ($data->type) {
            case self::TYPE_IMAGE:
                $output = Html::tag(
                        "h3",
                        Html::encode(ArrayHelper::getValue($data, 'title')),
                        ['class' => 'text-center']
                    ).
                Html::img(Html::encode($data->src), [
                    'alt' => Html::encode($data->alt),
                    'class' => 'img-responsive center-block'
                ]).
                Html::tag(
                    "p",
                    Html::encode(ArrayHelper::getValue($data, 'description'))
                );
                break;

            case self::TYPE_VIDEO:
                $output = Html::tag(
                        "h3",
                        Html::encode(ArrayHelper::getValue($data, 'title')),
                        ['class' => 'text-center']
                    ).
                    Html::tag(
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
                    ).
                    Html::tag(
                        "p",
                        Html::encode(ArrayHelper::getValue($data, 'description'))
                    );
                break;

            case self::TYPE_LINK:
                $output = Html::a(
                        isset($data->title) ? $data->title : $data->src,
                        Html::encode($data->src)
                    ).
                    Html::tag(
                        "p",
                        Html::encode(ArrayHelper::getValue($data, 'description'))
                    );
                break;

            default:
                throw new Exception("Type is not supported!");
        }

        return $output;
    }

}