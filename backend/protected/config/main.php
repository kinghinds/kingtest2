<?php

defined('BACKEND') or define('BACKEND', 'BACKEND');
defined('FRONTEND') or define('FRONTEND', 'FRONTEND');
defined('STAGE') or define('STAGE', 'BACKEND');

if (isset($_POST['PHPSESSID'])) {
	session_id($_POST['PHPSESSID']);
}

$basePath = dirname(dirname(__FILE__));
$frontendBasePath = dirname(dirname($basePath)) . '/protected';
Yii::setPathOfAlias('frontendBasePath', $frontendBasePath);

return array(
	'basePath' => $basePath,
	'name' => 'JUNGE',
	'language' => 'zh_cn',
	'sourceLanguage' => 'zh_cn',
	'timeZone' => 'Asia/Chongqing',
	'preload' => array(
		'log'
	),
	'import' => array(
		'application.helpers.*',
		'application.extensions.*',
		'application.vendors.*',
		'application.models.*',
		'application.components.*'
	),
	'components' => array(
		'user' => array(
			'class' => 'WebUser'
		),
		'excel'=>array(
          	'class'=>'application.extensions.phpexcel.Classes.PHPExcel',
        	),
		'authManager'=>array(
			'class' => 'AuthManager'
		),
		'db' => include dirname(__FILE__) . '/db.php',
		'errorHandler' => array(
			'errorAction' => 'site/error'
		),
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => (YII_DEBUG ? 'error, warning, trace, info, profile' : 'error, warning'),
					'categories' => (YII_DEBUG ? 'system.db.CDbCommand,devel.*' : ''),
				)
			)
		),
		'cache' => array(
			'class' => 'CFileCache'
		)
	),
	'params' => include dirname(__FILE__) . '/params.php'
);

?>