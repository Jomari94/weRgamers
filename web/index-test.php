<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

define('YII_ENV', getenv('YII_ENV') ?: 'dev');
define('YII_DEBUG', getenv('YII_DEBUG') ?: YII_ENV == 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/test.php');

(new yii\web\Application($config))->run();
