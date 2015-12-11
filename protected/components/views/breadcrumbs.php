<?php 
$this->widget('DBreadcrumbs', array(
		'links' => $links,
		'homeLink' => CHtml::tag('label', array('class' => 'label'), Yii::t('common', 'Your location: '))
				. CHtml::link(Yii::t('common', 'Home'), array('site/index')), 
		'separator' => '',
		'htmlOptions' => array('class' => 'breadcrumb')
));
?>