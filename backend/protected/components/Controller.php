<?php

class Controller extends CController {
	public $breadcrumbs = array();
	
	protected function beforeAction($action) {
		if (!parent::beforeAction($action))
			return false;
		$route = $this->id . '/' . $action->id;
		$allowed = include dirname(__FILE__) . '/../config/allowed.php';
		//Helper::dump($allowed,1);
		if (Yii::app()->user->isGuest && !in_array($route, $allowed))
			Yii::app()->user->loginRequired();
		defined('COLON')
				or define('COLON',
						I18nHelper::getFrontendLocalizedParam('colon'));
		return true;
	}
	
	public function redirectMessage($message, $url, $delay = 5, $script = '') {
		$this->pageTitle = 'Please stand by...';
		$this->renderPartial('/redirect',
				array(
						'message' => $message,
						'url' => $url,
						'delay' => $delay,
						'script' => $script,
				));
		Yii::app()->end();
	}
	
	/*public function printText($text) {
	 echo $text;
	 Yii::app()->end();
	 }
	
	 public function printJson($value) {
	 echo json_encode($value);
	 Yii::app()->end();
	 }*/
	
	/**
	 * $type includes:
	 * info => Information Messages
	 * succ => Success Messages
	 * warn => Warning Messages
	 * err => Error Message
	 *
	 */
	public function setFlashMessage($value, $type = null) {
		if (is_null($type))
			$type = 'succ';
		elseif (!in_array($type, array(
				'info',
				'succ',
				'warn',
				'err'
		)))
			throw new Exception(
					'"' . $type . '" is undefined type of flash message');
		return Yii::app()->user->setFlash('message', $type . '#' . $value);
	}

	/* 
	 * 获取返回地址
	 * @returnUrl 指定返回地址
	 */
	public function getReturnUrl($returnUrl = null) {
		if (isset($_GET['return_url']) && empty($_GET['return_url']) == false) {
			$returnUrl = Yii::app()->request->getQuery('return_url');
		} else if (empty($returnUrl) == true) {
			$returnUrl = array('index');
		}
		return $returnUrl;
	}
}
