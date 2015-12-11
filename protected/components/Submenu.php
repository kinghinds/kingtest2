<?php

class Submenu extends CWidget {

	public function run() {
		$currentPageId = Yii::app()->controller->menuId;
		$currentPage = Page::model()->localized()->findByPk($currentPageId);
		if (is_null($currentPage)) {
			return;
		}

		$parentPage = Page::model()->localized()->findByPk($currentPage->parent_id);
		if (is_null($parentPage)) {
			return;
		}

		$criteria = new CDbCriteria();
		$criteria->compare('parent_id', $parentPage->page_id);
		$criteria->order = 'sort_order ASC';
		$brotherPages = Page::model()->localized()->findALl($criteria);

		$items = array();
		foreach ($brotherPages as $i => $page) {
			$items[$i] = array(
				'label' => CHtml::tag('span', array(), $page->title),
				'url' => $page->getPermalink(),
				'linkOptions' => (empty($page->target_window) ? array() : array('target' => $page->target_window)),				
				'active' => $page->page_id == $currentPageId
			);
		}

		$this->render('submenu', array(
			'items' => $items			
		));		
	}

	protected function getChildrens($parentCategories, $selectedIdList,
			$parentId, $layer = 0) {
		$arr = array();
		foreach ($parentCategories as $i => $parentCategory) {
			if ($parentCategory->parent_id == $parentId) {
				$arr[$i] = array('label' => $parentCategory->title,
						'url' => $parentCategory->getPermalink(),
						'active' => in_array(
								$parentCategory->product_category_id,
								$selectedIdList));

				$layer++;
				$arr[$i]['items'] = self::getChildrens($parentCategories,
						$selectedIdList, $parentCategory->product_category_id,
						$layer);
				$layer--;
			}
		}

		return $arr;
	}

	protected function getParentIdList($parentCategories, $currentId) {
		$arr = array();
		foreach ($parentCategories as $parentCategory) {
			if ($parentCategory->product_category_id == $currentId) {
				array_push($arr, $currentId);
				$arr = array_merge($arr,
						self::getParentIdList($parentCategories,
								$parentCategory->parent_id));
			}
		}
		return $arr;
	}
}

?>