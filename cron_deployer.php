<?php
$fileName = "start_deploy.php";

if (file_exists($fileName)) {
    unlink($fileName);
    exec("dep deploy > deploy_log.txt");
}