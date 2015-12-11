<?php

class BackgroundWidget extends CWidget {
	public function run() {
		$menuId = Yii::app()->controller->menuId;
		$page = Page::model()->localized()->findByPk($menuId);
		if (is_null($page)) {
			return;
		}

		$bgImageUrl = false;
		if ($page->getBgImageUrl()) {
			$bgImageUrl = $page->getBgImageUrl();
		} else {
			$rootId = Page::model()->getRootIdByPk($menuId);
			if ($rootId == false) {
				return;
			}

			$rootPage = Page::model()->localized()->findByPk($rootId);
			if (is_null($rootPage) == false && $rootPage->getBgImageUrl()) {
				$bgImageUrl = $rootPage->getBgImageUrl();
			} 
		}		

		$this->render('background', array(
				'bgImageUrl' => $bgImageUrl
		));
	}
}

?>