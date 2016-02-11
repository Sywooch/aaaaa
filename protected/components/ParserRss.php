<?php
namespace app\components;

use yii\base\Exception;
use yii\helpers\ArrayHelper;

class ParserRss implements Parser
{
    /*
     * Парсинг данных
     */
    public function parse(\simple_html_dom $dom)
    {
        // тут вся логика парсера - разбор и формирование массива Постов
        /* @var $items array */
        $items = $dom->find('item');
        if (empty($items)) {
            return [];
        }

        /* @var $item \simple_html_dom */
        $posts = [];
        foreach ($items as $item) {
            if (
                preg_match_all(
                    '#((http|https)://[^\?\&\#\s]+\.(jpg|jpeg|gif|png))#U',
                    $item->find('description', 0)->innertext,
                    $matches
                )
            ) {
                $images = [];
                foreach ($matches[1] as $src) {
                    $images[] = [
                        'type' => ContentGenerator::TYPE_IMAGE,
                        'src' => $src,
                        'alt' => '',
                        'title' => '',
                        'description' => '',
                    ];
                }
                $posts[] = (count($images) == 1) ? json_encode($images[0]) : json_encode($images);
            }
        }

        return $posts;
    }

}