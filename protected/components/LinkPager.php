<?php

class LinkPager extends CWidget {
	
	public $pages;
	
	public function run() {
		$this->render('linkPager', array(
				'pages' => $this->pages
		));
	}
}

?>