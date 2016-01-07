<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc><?= Yii::$app->params['siteUrl'] ?>main.xml</loc>
        <lastmod><?= date("Y-m-d") ?></lastmod>
    </sitemap>
    <?php foreach ($sitemaps as $sitemap): ?>
    <sitemap>
        <loc><?= $sitemap['loc'] ?></loc>
        <lastmod><?= $sitemap['lastmod'] ?></lastmod>
    </sitemap>
    <?php endforeach; ?>
</sitemapindex>