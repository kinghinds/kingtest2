<!-- sidebar -->
<div class="con_left">
	<div class="sidecon">
		<h3 class="title"><?php echo Yii::t('common','人才招聘');?></h3>
		<?php $this->widget('zii.widgets.CMenu', array(
			'items' => $items,
			'activeCssClass' => 'active',
			'htmlOptions'=>array('class'=>'sidebar'),

		)); ?>
   </div>
</div>
<!-- /sidebar -->
