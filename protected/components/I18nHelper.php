<?php

class I18nHelper {
	
	public static function getLocalizedParam($key) {
		$languages = include dirname(__FILE__) . '/../config/languages.php';
		return $languages[Yii::app()->language][$key];
	}
	
	public static function getLanguages($excludeSourceLanguage = true) {
		$languages = include dirname(dirname(__FILE__))
				. '/config/languages.php';
		if ($excludeSourceLanguage)
			unset($languages[Yii::app()->sourceLanguage]);
		return $languages;
	}
	
	public static function getLanguageKeys($excludeSourceLanguage = true) {
		$languages = include dirname(dirname(__FILE__))
				. '/config/languages.php';
		if ($excludeSourceLanguage)
			unset($languages[Yii::app()->sourceLanguage]);
		return array_keys($languages);
	}

	public static function getFrontendLanguages($excludeSourceLanguage = true) {
		return array();
	}

	public static function getFrontendLanguageKeys($excludeSourceLanguage = true) {
		return array();
	}	
}

?>