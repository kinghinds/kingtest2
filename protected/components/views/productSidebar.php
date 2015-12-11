<!-- sidebar -->
<div class="pro_left">
	<div class="pronav">
		<h3 class="title"><?php echo $title; ?></h3>
		<?php $this->widget('zii.widgets.CMenu', array(
			'items' => $items,
			'activeCssClass' => 'active',
			'htmlOptions'=>array('class'=>'probar')
		)); ?>
   </div>
</div>
<!-- /sidebar -->
