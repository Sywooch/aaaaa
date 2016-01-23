<?php
namespace app\components;

use Abraham\TwitterOAuth\TwitterOAuth;
use app\models\Post;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\Exception;

class ContentGenerator
{
    const TYPE_IMAGE = "image";
    const TYPE_VIDEO = "video";
    const TYPE_TEXT = "text";
    const TYPE_LINK = "link";

    /*
     * JSON в HTML
     */
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

    /*
     * Текст поста HTML в JSON представление
     */
    public static function Format($text)
    {
        // проверка на json или чистый текст, не обрабатывать
        if ($data = json_decode($text) || !mb_strpos($text, '://')) {
            return $text;
        }

        // одиночные ссылки на материалы
        if (preg_match('#^[\s]*(https://www.youtube.com/embed/[\w\-]+)[\s]*$#', $text, $src)) {
            return json_encode(['type' => self::TYPE_VIDEO, 'src' => $src[1], 'title' => '', 'description' => '']);
        }
        if (preg_match('#^[\s]*((http|https)://[^\?\&\#\s]+\.(jpg|jpeg|gif|png))[\s]*$#', $text, $src)) {
            return json_encode(['type' => self::TYPE_IMAGE, 'src' => $src[1], 'alt' => '', 'title' => '', 'description' => '']);
        }
        // групповые ссылки на материалы
        // --

        // иначе разбор поста
        // video
        $text = preg_replace(
            '#(https://www.youtube.com/embed/[\w\-]+)#',
            Html::tag(
                "iframe",
                null,
                [
                    'src' => "$1",
                    'class' => 'embed-responsive-item',
                    'allowfullscreen' => true,
                    'style' => ['border' => 'none'],
                    'height' => '400',
                    'width' => '100%',
                ]
            ),
            $text
        );
        // image
        $text = preg_replace(
            '#((http|https)://[^\?\&\#\s]+\.(jpg|jpeg|gif|png))#',
            //'<br><img class="img-responsive center-block" src="$1"/>',
            '<br>' . Html::img("$1", [
                'alt' => 'Картинка',
                'class' => 'img-responsive center-block'
            ]),
            $text
        );
        // link
        $text = preg_replace(
            '#[^\"]{1}((http|https)://[^\s]+)[^\"]{1}#',
            ' <a href="$1">Ссылка</a> ',
            $text
        );

        return $text;
    }

    /*
     * Публикация в твиттере
     */
    public static function Twitter(Post $post)
    {
        if (!\Yii::$app->params['twitter']) {
            return false;
        }

        // autoload.php does not work
        require_once ('protected/vendor/abraham/twitteroauth/autoload.php');
        $twitter = new TwitterOAuth(
            ArrayHelper::getValue(\Yii::$app->params['twitter'], 'consumerKey'),
            ArrayHelper::getValue(\Yii::$app->params['twitter'], 'consumerSecret'),
            ArrayHelper::getValue(\Yii::$app->params['twitter'], 'oauthToken'),
            ArrayHelper::getValue(\Yii::$app->params['twitter'], 'oauthTokenSecret')
        );

        if ($data = json_decode($post->text)) {
            switch ($data->type) {
                case self::TYPE_IMAGE:
                    $media = $twitter->upload(
                        'media/upload',
                        ['media' => $data->src]
                    );
                    $statuses = $twitter->post(
                        "statuses/update",
                        [
                            "status" => "Новое фото " . \Yii::$app->params['siteUrl'] . 'post/' . $post->id .
                                " #" . implode(' #', ArrayHelper::map(ArrayHelper::getValue($post, 'tags'), 'id', 'name')),
                            "media_ids" => $media->media_id_string,
                        ]
                    );
                    break;
                case self::TYPE_VIDEO:
                    $statuses = $twitter->post(
                        "statuses/update",
                        [
                            "status" => "Новое видео " . \Yii::$app->params['siteUrl'] . 'post/' . $post->id .
                                " #" . implode(' #', ArrayHelper::map(ArrayHelper::getValue($post, 'tags'), 'id', 'name')),
                        ]
                    );
                    break;
            }
        } else {
            $statuses = $twitter->post(
                "statuses/update",
                [
                    "status" => "Новый пост №" . $post->id . " " . \Yii::$app->params['siteUrl'] . 'post/' . $post->id .
                        " #" . implode(' #', ArrayHelper::map(ArrayHelper::getValue($post, 'tags'), 'id', 'name')),
                ]
            );
        }

    }
}