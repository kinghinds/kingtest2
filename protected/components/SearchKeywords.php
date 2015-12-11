<?php

class SearchKeywords extends CWidget {

	public $keywords = array();

	public function run() {	
		if (empty($this->keywords)) {
			return;
		}

		$keywords = explode(',', $this->keywords);
		$items = array();
		foreach ($keywords as $keyword) {
			$items[] = array(
					'label' => $keyword,
					'url' => Yii::app()->createUrl(
							'site/search', 
							array('q' => $keyword)
					)
			);
		}

		$this->render('searchKeywords', array(
				'items' => $items
		));
	}
}

?>