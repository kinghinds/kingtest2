<?php $this->beginClip('extraHead'); ?>
<?php Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl()
			. '/jui/css/base/jquery-ui.css'); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php $this->endClip(); ?>

<div class="heading">
	<h1>
		<?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>
		查看咨询回复
	</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('updateAnswer')) { ?>
		<?php echo CHtml::link('<span>修改</span>', array('update', 
				'id' => $answer->primaryKey, 'return_url' => $returnUrl), array(
				'class' => 'button')); ?>
		<?php } ?>
		<?php echo CHtml::link('<span>返回</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>
<div class="content">

	<?php ob_start(); ?>
	<?php $this->widget('zii.widgets.CDetailView', array(
		'htmlOptions' => array('class' => 'form'),
		'data' => $answer,
		'attributes' => array(
			array('name' => 'feedback_id',
				'value' => ($answer->feedback->content)
			),
			
			array(
				'name' => 'content',
				'type' => 'html'
			),
			array('name' => 'reply_time'),
		)
	));	?>
	<?php $basicContent = ob_get_clean(); ?>
	<?php $tabs = array('基本资料' => $basicContent); ?>
	<?php $this->widget('zii.widgets.jui.CJuiTabs', array('tabs' => $tabs)); ?>

</div>
