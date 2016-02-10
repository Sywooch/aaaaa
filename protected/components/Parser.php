<?php
namespace app\components;

use yii\base\Exception;

interface Parser
{
    /*
     * Парсинг постов из данных
     */
    public function parse($data);

}