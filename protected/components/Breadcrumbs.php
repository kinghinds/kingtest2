<?php

class Breadcrumbs extends CWidget {

	public $breadcrumbs = array();

	public function run() {
		if ($this->breadcrumbs) {
			$breadcrumbs = $this->breadcrumbs;
		} else {
			$breadcrumbs = Yii::app()->controller->breadcrumbs;
		}
		if (count($breadcrumbs) <= 0) {
			return;
		}

		$this->render('breadcrumbs', array(
				'links' => $breadcrumbs
		));
	}
}

?>