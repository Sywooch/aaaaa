<?php
/*
 * This file has been generated automatically.
 * Please change the configuration for correct use deploy.
 */

//require 'recipe/yii2-app-basic.php';
require 'recipe/common.php';

// Set configurations
set('repository', 'git@github.com:mamontovdmitriy/aaaaa.git');
set('shared_files', []);
set('shared_dirs', []);
set('writable_dirs', ['assets']);

// Configure servers
//server('production', 'aaaaa.phptest.info')
//    ->user('root')
//    ->identityFile('~/.ssh/id_rsa.pub', '~/.ssh/id_rsa', '')
//    ->env('deploy_path', '/var/www/aaaaa');

localServer("local")
    ->user("www-data")
    ->env('deploy_path', '/var/www/aaaaa');

/**
 * Run migrations
 */
task('deploy:configure_db', function () {
    run("echo '<?php' > {{release_path}}/db.php");
    run("echo 'return [' >> {{release_path}}/db.php");
    run("echo \"   'class' => 'yii\\db\\Connection',\" >> {{release_path}}/db.php");
    run("echo \"   'dsn' => 'mysql:host=localhost;dbname=test_dep_aaaaa',\" >> {{release_path}}/db.php");
    run("echo \"   'username' => 'test',\" >> {{release_path}}/db.php");
    run("echo \"   'password' => '',\" >> {{release_path}}/db.php");
    run("echo \"   'charset' => 'utf8',\" >> {{release_path}}/db.php");
    run("echo \"];\" >> {{release_path}}/db.php");
})->desc('Configure database connection');

/**
 * Run migrations
 */
task('deploy:run_migrations', function () {
    run('{{bin/php}} {{release_path}}/yii migrate up --interactive=0');
})->desc('Run migrations');

/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:configure_db',
    'deploy:run_migrations',
    'deploy:symlink',
    'deploy:writable',
    'cleanup',
])->desc('Deploy your project');

after('deploy', 'success');


/**
 * Restart php-fpm on success deploy.
 */
//task('php-fpm:restart', function () {
//    // Attention: The user must have rights for restart service
//    // Attention: the command "sudo /bin/systemctl restart php-fpm.service" used only on CentOS system
//    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
//    run('sudo /bin/systemctl restart php-fpm.service');
//})->desc('Restart PHP-FPM service');
//
//after('success', 'php-fpm:restart');