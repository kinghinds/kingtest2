<?php

class Controller extends CController {

	public $metaKeywords;
	public $metaDescription;
	public $breadcrumbs = array();
	public $menuId;
	public $productCategoryId;
	public $memberSidebarId;

	# override
	protected function beforeAction($action) {
		if (!parent::beforeAction($action))
			return false;
		/*
		 if(Yii::app()->language!=Yii::app()->sourceLanguage) {
		 Yii::app()->params['appName']=Yii::t('app','@appName');
		 Yii::app()->params['appShortName']=Yii::t('app','@appShortName');
		 }
		 */
		defined('SEPARATOR')
				or define('SEPARATOR',
						I18nHelper::getLocalizedParam('separator'));
		defined('COLON')
				or define('COLON', I18nHelper::getLocalizedParam('colon'));
		defined('DATE_FORMAT')
				or define('DATE_FORMAT',
						I18nHelper::getLocalizedParam('date_format'));
		defined('SHORT_DATE_FORMAT')
				or define('SHORT_DATE_FORMAT',
						I18nHelper::getLocalizedParam('short_date_format'));
		defined('TIME_FORMAT')
				or define('TIME_FORMAT',
						I18nHelper::getLocalizedParam('time_format'));
		defined('MONTH_FORMAT')
				or define('MONTH_FORMAT',
						I18nHelper::getLocalizedParam('month_format'));
		return true;
	}

	public function redirectMessage($message, $url, $delay = 5, $script = '') {
		$this->pageTitle = Yii::t('common', 'Please wait...');
		$this
				->render('/redirect',
						array('message' => $message, 'url' => $url,
								'delay' => $delay, 'script' => $script,));
		Yii::app()->end();
	}
}

?>