<?php
namespace app\components;

use yii\base\Exception;

class ParserTwitter implements Parser
{
    /*
     * Парсинг данных из Twitter
     */
    public function parse(\simple_html_dom $dom)
    {
        // тут вся логика парсера - разбор и формирование массива Постов

        // пока не надо
        return [];
    }

}