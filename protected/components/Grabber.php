<?php
namespace app\components;

use app\models\Source;
use yii\base\Exception;
use app\components\ParserRSS;

class Grabber
{
    private $data;
    private $parser;

    public function __construct(Source $source)
    {
        try {
            $this->data = file_get_contents($source->url);
            $this->parser = $this->getParser($source->parser);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function execute()
    {
        // получить посты
        $newPosts = $this->parser->parse($this->data);

        return $newPosts;
    }

    private function getParser($parserType)
    {
        $class = 'app\components\Parser' . $parserType;

        if (!class_exists($class)) {
            throw new Exception("Это пока не реализовано!");
        }

        return new $class;
    }
}