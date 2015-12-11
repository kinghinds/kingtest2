<?php

class Menu extends CWidget {
	public function run() {
		$items = array();

		if (Yii::app()->user->isGuest == false) {
		 	$items = Yii::app()->user->getState('menuItems');
		 	/*$home = array(array(
		 		'label' => '首页',
		 		'url' => array('site/index')
		 	));
		 	$items = array_merge($home, $items);*/
		 }
	
		foreach ($items as $i => $item) {
			$items[$i]['linkOptions'] = array('class' => 'top');
			$items[$i]['submenuOptions'] = array('style' => 'display:none;');
		}		

		$this->widget('zii.widgets.CMenu', array(
			'items' => $items, 
			'encodeLabel' => false,
			'htmlOptions' => array('class' => 'left')
		));
	}
}

?>