<div style="margin-left: 20px;">
	<?php
switch ($code) {
case 404:
	$this->pageTitle = $message;
	$this->breadcrumbs = array('访问错误');
	echo '<h2>访问错误</h2><div class="error">'
			. preg_replace('#(".*")#', '<font color="red">$1</font>', $message)
			. '</div>';
	break;

case 403:
	$this->pageTitle = $message;
	$this->breadcrumbs = array('权限不足');
	echo '<h2>权限不足</h2><div class="error">' . CHtml::encode($message)
			. '</div>';
	break;

default:
	$this->pageTitle = $message;
	$this->breadcrumbs = array('不知名错误');
	echo '<h2>错误代码 ' . $code . '</h2><div class="error">'
			. Html::encode($message) . '</div>';
}
	?> 
	<?php if ($urlReferrer = Yii::app()->request->urlReferrer) { ?>
	<p>Referrer<?php echo COLON; ?><?php echo CHtml::link($urlReferrer,
			$urlReferrer);
								   ?>
	</p>
	<?php } ?>
</div>
