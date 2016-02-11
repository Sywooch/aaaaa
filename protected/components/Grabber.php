<?php
namespace app\components;

use app\models\Source;
use yii\base\Exception;
use garyjl\simplehtmldom\SimpleHTMLDom;

class Grabber
{
    /* @var $dom \simple_html_dom */
    private $dom;

    /* @var $parser \app\components\Parser */
    private $parser;

    public function __construct(Source $source)
    {
        try {
            $this->dom = SimpleHTMLDom::str_get_html($this->getData($source->url));
            $this->parser = $this->getParser($source->parser);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function __destruct()
    {
        $this->dom->clear();
        unset($this->dom);
    }

    /**
     * @return array
     */
    public function execute()
    {
        // получить посты
        $newPosts = $this->parser->parse($this->dom);

        return $newPosts;
    }

    /**
     * @param $parserType string
     * @return Parser
     * @throws Exception
     */
    private function getParser($parserType)
    {
        $class = 'app\components\Parser' . $parserType;

        if (!class_exists($class)) {
            throw new Exception("Это пока не реализовано!");
        }

        return new $class;
    }

    /**
     * обход бана по user_agent
     *
     * @param $url string
     * @return string
     */
    private function getData($url)
    {
        $path_to_cookie = '@web/cookie.txt';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2');
        curl_setopt($ch, CURLOPT_HEADER, 0); // Пустые заголовки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Возвратить то что вернул сервер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Следовать за редиректами
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// Таймаут
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, \Yii::getAlias($path_to_cookie)); // Сохранять куки в файл
        curl_setopt($ch, CURLOPT_COOKIEFILE, \Yii::getAlias($path_to_cookie));
        curl_setopt($ch, CURLOPT_POST, false);

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}