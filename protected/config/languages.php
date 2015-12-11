<?php

/**
 * 配置所有前台语言版本
 * 第一项必须指定为“源”版本，一般为 "en" 英文版本
 * 支持提示信息的语言版请查看YII框架\framework\messages文件夹
 */
return array(
	'zh_cn' => array(
		'label' => '简体中文',
		'short_label' => '简',
		'separator' => '_',
		'icon' => '/style/img/zh_cn.png',
		'colon' => '：',
		'date_format' => 'Y年n月j日',
		'short_date_format' => 'n月j日',
		'time_format' => 'Y年n月j日 G:i',
		'month_format' => 'n月'
	),
	'en' => array(
		'label' => 'English',
		'short_label' => '英',
		'icon' => '/style/img/en.png',
		'separator' => ' - ',
		'colon' => ':',
		'date_format' => 'M jS, Y',
		'short_date_format' => 'M jS',
		'time_format' => 'M jS, Y G:i',
		'month_format' => 'M'
	),
);

?>