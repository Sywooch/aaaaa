<?php
/*
 * запускает деплой при наличии файла
 */
$fileName = "/var/www/deploy/start_deploy.php";

if (file_exists($fileName)) {
    unlink($fileName);
    exec("cd /var/www/deploy && dep deploy > deploy_log.txt");
}