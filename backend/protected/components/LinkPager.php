<?php

class LinkPager extends CLinkPager {
	
	public $pageSizes = array(
			10,
			20,
			50,
			100
	);
	
	public function run() {
		$this->registerClientScript();
		$buttons = $this->createPageButtons();
		if (empty($buttons) == false) {
			echo $this->header;
			echo CHtml::tag('ul', $this->htmlOptions, implode("\n", $buttons));
			echo $this->footer;
			
			echo ' ' . Yii::t('yii', 'Go to page: ');
			$currentPage = Yii::app()->request->getQuery(
					$this->pages->pageVar, $this->getCurrentPage());
			echo CHtml::dropDownList($this->id . 'pn', $currentPage,
					$this->createPageNoOptions());
		}
		
		$displayPageSize = true;
		if ($this->pageCount == 0
				|| ($this->pageCount == 1 && isset($this->pageSizes[0])
						&& $this->pageSize == $this->pageSizes[0])) {
			$displayPageSize = false;
		}
		if ($displayPageSize == true) {
			echo '&nbsp;<span style="float:right">';
			//echo Yii::t('yii', 'Page size: ');
			echo '每页记录数量: ';
			echo CHtml::dropDownList($this->id . 'ps', $this->pageSize,
					$this->createPageSizeOptions());
			echo '</span>';
		}
		
		$cs = Yii::app()->clientScript;
		$js = '';
		if (empty($buttons) == false) {
			$js .= "jQuery('#" . $this->id . "pn').live('change', function() {";
			$js .= "$.fn.yiiGridView.update('" . $this->getOwner()->id . "',{";
			$js .= "data:{ " . $this->pages->pageVar . ": $(this).val() },";
			$js .= "});";
			$js .= "});";
			$js .= "\r\n";
		}
		if ($displayPageSize == true) {
			$js .= "jQuery('#" . $this->id . "ps').live('change', function() {";
			$js .= "$.fn.yiiGridView.update('" . $this->getOwner()->id . "',{";
			$js .= "data:{ pagesize : $(this).val() },";
			$js .= "});";
			$js .= "});";
		}
		$cs->registerScript(__CLASS__ . '#' . $this->getOwner()->id, $js);
	}
	
	protected function createPageNoOptions() {
		$pageCount = $this->getPageCount();
		if ($pageCount <= 1)
			return array();
		
		$options = array();
		for ($i = 1; $i <= $pageCount; $i++) {
			$options[$i] = $i;
		}
		
		return $options;
	}
	
	protected function createPageSizeOptions() {
		$options = array();
		foreach ($this->pageSizes as $pageSize) {
			$options[$pageSize] = $pageSize;
		}
		return $options;
	}
}

?>