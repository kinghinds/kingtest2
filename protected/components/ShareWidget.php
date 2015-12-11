<?php

class ShareWidget extends CWidget {
	public function run() {
		$js = <<<EOP
$(function() {
	$('.print').click(function(){
		window.print();
		return false;
	});
});
EOP;

		Yii::app()->clientScript->registerScript(get_class($this), $js, 
				CClientScript::POS_END);

		$this->render('share');
	}
}

?>