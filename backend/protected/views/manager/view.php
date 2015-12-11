<?php $this->beginClip('extraHead'); ?>
<?php Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl()
			. '/jui/css/base/jquery-ui.css'); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php $this->endClip(); ?>

<div class="heading">
	<h1>
		<?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>
		查看管理员
	</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('updateManager')) { ?>
		<?php echo CHtml::link('<span>修改</span>', array('update', 
				'id' => $manager->manager_id, 'return_url' => $returnUrl), array(
				'class' => 'button')); ?>
		<?php } ?>
		<?php echo CHtml::link('<span>返回</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>
<div class="content">

	<?php $this->widget('zii.widgets.CDetailView', array(
		'htmlOptions' => array('class' => 'form'),
		'data' => $manager,
		'attributes' => array(
			array('name' => 'login_name'),
			array(
				'label' => $manager->getAttributeLabel('manager_role_id'),
				'name' => 'managerRole.name',
			),
			array('name' => 'last_login_time'),
			array('name' => 'last_login_ip'),
			array('name' => 'login_times'),
			array(
				'name' => 'is_allow_login',
				'value' => ($manager->is_allow_login ? '是' : '否')
			)
		)
	));	?>

</div>
