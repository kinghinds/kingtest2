<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/user-group.png'); ?>查看管理员角色</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('updateManagerRole')) { ?>
		<?php echo CHtml::link('<span>修改</span>', array('update', 
				'id' => $managerRole->manager_role_id,
				'return_url' => $returnUrl), 
				array('class' => 'button')); ?>
		<?php } ?>
		<?php echo CHtml::link('<span>返回</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>

<div class="content">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'htmlOptions' => array('class' => 'form'),
		'data' => $managerRole,
		'attributes' => array(
			array('name' => 'name'),
			array('name' => 'description'),
			array('name' => 'create_time'),
			array(
				'name' => 'update_time',
				'visible' => (strtotime($managerRole->update_time) > 0)
			)
		)
	));	?>
</div>
