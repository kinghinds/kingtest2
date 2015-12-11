<?php $this->beginClip('extraHead'); ?>
<?php Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl()
			. '/jui/css/base/jquery-ui.css'); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php $this->endClip(); ?>

<div class="heading">
	<h1>
		<?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>
		查看问题咨询
	</h1>
	<div class="buttons">
		<?php if($feedback->is_reply == 0) { ?>
		<?php echo CHtml::link('<span>回复</span>', array('answer/reply', 
				'feedbackid' => $feedback->primaryKey), array(
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
		'data' => $feedback,
		'attributes' => array(			
			array(
				'name' => 'content',
				'type' => 'html'
			),
			array('name' => 'create_time'),
			array(
				'name' => 'is_reply',
				'value' => ($feedback->is_reply ? '已回复' : '未回复')
			)
		)
	));	?>
	<?php $basicContent = ob_get_clean(); ?>
	<?php if($feedback->is_reply == 1){ ?>
	<?php ob_start(); ?>
	<?php $this->widget('zii.widgets.CDetailView', array(
		'htmlOptions' => array('class' => 'form'),
		'data' => $answer,
		'attributes' => array(
			array(
				'name' => 'content',
				'type' => 'html'
			),
			array('name' => 'reply_time'),
		)
	));	?>
	<?php $answerContent = ob_get_clean(); ?>
	
	<?php $tabs = array('基本资料' => $basicContent,'咨询回复'=>$answerContent); ?>
	<?php } else { ?>
	<?php $tabs = array('基本资料' => $basicContent); ?>
	<?php } ?>
	<?php $this->widget('zii.widgets.jui.CJuiTabs', array('tabs' => $tabs)); ?>

</div>
