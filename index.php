<?php

$yii = realpath('yii/framework/yii.php');
$config = realpath('protected/config/main.php');

defined('STAGE') or define('STAGE', 'FRONTEND');

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
if (defined('YII_DEBUG') && YII_DEBUG) {
    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
}

require_once($yii);
Yii::createWebApplication($config)->run();

?>