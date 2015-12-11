<?php

class Menu extends CWidget {
	public function run() {
		// 主菜单
		$menuId = Yii::app()->controller->menuId;
		$rootId = Page::model()->getRootIdByPk($menuId);
		if ($rootId == false) {
			$rootId = $menuId;
		}
		$items = array();
		$criteria = new CDbCriteria();
		$criteria->compare('parent_id', '0');
		$criteria->compare('is_indexable', '1');
		$criteria->order = 'sort_order ASC';
		$pages = Page::model()->localized()->findAll($criteria);

		foreach ($pages as $i => $page) {
			$items[$i] = array(
					'template' => '{menu}',
					'label' => $page->title,
					'url' => $page->getPermalink(),
			);
			if ($i == 0) {
				$items[$i]['itemOptions'] = array('class' => $page->primaryKey == $rootId ? 'line active' : 'line');
			}
			else
			{
				if ($page->primaryKey == $rootId) 
					$items[$i]['itemOptions'] = array('class' => 'active');
			}
			

		}
		if($menuId == 0){
		$items = array_merge(
				array(
						array('label' => Yii::t('common', '首页'),
								'url' => array('site/index'),
								'itemOptions'=>array('class' => 'cur')
			)), $items);
		}else{
		$items = array_merge(
				array(
						array('label' => Yii::t('common', '首页'),
								'url' => array('site/index')
			)), $items);
		}
		
		$this->render('menu', array(
				'items' => $items
		));
		
	}
}

?>
