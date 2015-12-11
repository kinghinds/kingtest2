<?php

class I18nHelper {
	
	public static function getFrontendLocalizedParam($key) {
		$languages = include Yii::getPathOfAlias('frontendBasePath')
				. '/config/languages.php';
		return $languages[Yii::app()->language][$key];
	}
	
	public static function getFrontendLanguages($excludeSourceLanguage = true) {
		$languages = include Yii::getPathOfAlias('frontendBasePath')
				. '/config/languages.php';
		if ($excludeSourceLanguage)
			array_shift($languages);
		return $languages;
	}
	
	public static function getFrontendLanguageKeys(
			$excludeSourceLanguage = true) {
		$languages = include Yii::getPathOfAlias('frontendBasePath')
				. '/config/languages.php';
		if ($excludeSourceLanguage)
			array_shift($languages);
		return array_keys($languages);
	}
	
	public static function getFrontendSourceLanguage() {
		$languages = include Yii::getPathOfAlias('frontendBasePath')
				. '/config/languages.php';
		$keys = array_keys($languages);
		return reset($keys);
	}
	
}
