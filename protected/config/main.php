<?php
defined('BACKEND') or define('BACKEND', 'BACKEND');
defined('FRONTEND') or define('FRONTEND', 'FRONTEND');
defined('STAGE') or define('STAGE', 'FRONTEND');

$basePath = dirname(dirname(__FILE__));
$backendBasePath = dirname($basePath) . '/backend/protected';
Yii::setPathOfAlias('backendBasePath', $backendBasePath);
$languages = array_keys(include dirname(__FILE__) . '/languages.php');

return array(
		'basePath' => $basePath, 
		'name' => 'Junge',
		'sourceLanguage' => 'zh_cn', 
		'language' => 'zh_cn',
		'timeZone' => 'Asia/Chongqing',
		'onBeginRequest' => array('Helper', 'appOnBeginRequest'),
		'onEndRequest' => array('Helper', 'appOnEndRequest'),
		'preload' => array('log', 'ELangHandler'),
		'import' => array(
				'backendBasePath.components.AppSettings',
				'backendBasePath.extensions.JI18nActiveRecord',
				'backendBasePath.extensions.langhandler.*',
				'backendBasePath.models.*', 'backendBasePath.vendors.*',
				'application.models.*', 'application.components.*',
				'application.forms.*'
		),
		'components' => array(
				'db' => include $backendBasePath . '/config/db.php',
				'errorHandler' => array('errorAction' => 'site/error'),
				'ELangHandler' => array('class' => 'ELangHandler', 'languages' => $languages),
				'urlManager' => array(
						'class' => 'ELangCUrlManager',
						'showScriptName' => false, 
						'urlFormat' => 'path',
						'urlSuffix' => '.html',
						'rules' => array(
								// 优化

								// 通用
								'<lang:(en|zh_cn)>/index' => array('site/index'),
								'<lang:(en|zh_cn)>/product/category/<product_category_slug:[^\/]+>' => array(
										'product/index'),
								'<lang:(en|zh_cn)>/product/index' => array(
										'product/index'),
								'<lang:(en|zh_cn)>/product/view/id-<id:\d+>' => array(
										'product/view'),
								'<lang:(en|zh_cn)>/product/view/<slug:[^\/]+>' => array(
										'product/view'),
								'<lang:(en|zh_cn)>/product/search' => array(
										'product/search', 'urlSuffix' => false),
								'<lang:(en|zh_cn)>/faq/index' => array('faq/index'),
								'<lang:(en|zh_cn)>/feedback/index' => array(
										'feedback/index'),
								'<lang:(en|zh_cn)>/news/index/id-<id:\d+>' => array(
										'news/index'),
								'<lang:(en|zh_cn)>/news/index/<slug:[^\/]+>' => array(
										'news/index'),
								'<lang:(en|zh_cn)>/news/view/id-<id:\d+>' => array(
										'news/view'),
								'<lang:(en|zh_cn)>/news/view/<slug:[^\/]+>' => array(
										'news/view'),
								'<lang:(en|zh_cn)>/member/captcha' => array(
										'member/captcha', 'urlSuffix' => false),
								'<lang:(en|zh_cn)>/download/download/id-<id:\d+>' => array(
											'download/download', 'urlSuffix' => false),
								'<lang:(en|zh_cn)>/page/view/id-<id:\d+>' => array(
										'page/view'),
								'<lang:(en|zh_cn)>/page/view/<slug:[^\/]+>' => array(
										'page/view'),
								'<lang:(en|zh_cn)>/sitemap' => array(
										'site/sitemap'),
								'<lang:(en|zh_cn)>/<controller:\w+>/<action:\w+>' => array(
										'<controller>/<action>'))),
				'log' => array(
						'class' => 'CLogRouter',
						'routes' => array(
								array(
										'class' => 'CFileLogRoute',
										'levels' => (YII_DEBUG ? 'error, warning, trace, info, profile' : 'error, warning'),
										'categories' => (YII_DEBUG ? 'system.db.CDbCommand,devel.*' : '')
								)
						)
				),
				'cache' => array('class' => 'CFileCache'),
				'clientScript' => array(
						'scriptMap' => array(
								'jquery.js' => false
						)
				),
				/*'themeManager' => array(
						'basePath' => realpath('../themes/')
				)*/
		),
		// 'theme' => 'classic',
		'params' => include dirname(__FILE__) . '/params.php'
);
?>