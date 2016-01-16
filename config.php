<?php
$is_console = PHP_SAPI == 'cli' || (!isset($_SERVER['DOCUMENT_ROOT']) && !isset($_SERVER['REQUEST_URI']));

return $config = [
    'id' => 'aaaaakz',
    'language' => 'ru',
    'basePath' => __DIR__ . '/protected',
    'components' => [
        'user' => $is_console ? ['class' => ''] : [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
//            'dsn' => 'mysql:host=srv-db-plesk09.ps.kz:3306;dbname=aaaaakz_site',
//            'username' => 'aaaaa_site',
//            'password' => 'Gjek473*',
            'dsn' => 'mysql:host=localhost;dbname=aaaaa',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/<random:random>' => 'site/index',
                '/<action:[\w-]+>' => 'site/<action>',
                '/post/<post_id:[\d]+>' => 'site/post',
                '/<file:[\w_-]+>.xml' => 'site/sitemap',
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'rkvGRePaiPNtukv660iX51Je9PP0NLIf',
        ],
    ],
    'params' => [
        'adminEmail' => 'admin@aaaaa.kz',
        'name' => 'A-a-a-a-a!kz',
        'siteName' => 'Сборник свободного народного творчества',
        'siteUrl' => 'http://aaaaa.kz/',

        'googleAds' => <<<html
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- универсальный -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3550073859494126"
     data-ad-slot="6060494689"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
html
    ],
];