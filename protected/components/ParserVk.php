<?php
namespace app\components;

use yii\helpers\ArrayHelper;
use yii\base\Exception;
use garyjl\simplehtmldom\SimpleHTMLDom;

class ParserVk implements Parser
{
    /*
     * Парсинг данных из ВКонтакте
     */
    public function parse(\simple_html_dom $dom)
    {
        // тут вся логика парсера - разбор данных и формирование массива Постов
        /* @var $postsVk array */
        $postsVk = $dom->find('div[id^=wpt-]');
        if (empty($postsVk)) {
            return [];
        }

        /* @var $postVk \simple_html_dom */
        $posts = [];
        foreach ($postsVk as $postVk) {
            // Игнорим рекламу по вхождению "кидк" (Скидка)
            if (strpos($postVk->plaintext, "кидк") !== false) continue;
            // только картинки :)
            $posts[] = ArrayHelper::getValue($postVk->find('img', -1), 'src');
        }

        return $posts;
    }

}