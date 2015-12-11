<?php $this->beginClip('extraHead'); ?>
<?php Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl()
			. '/jui/css/base/jquery-ui.css'); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php $this->endClip(); ?>

<div class="heading">
	<h1>
		<?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>
		查看产品类型
	</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('updateBanner')) { ?>
		<?php echo CHtml::link('<span>修改</span>', array('update', 
				'id' => $category->banner_id, 'return_url' => $returnUrl), array(
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
		'data' => $category,
		'attributes' => array(
			array('name' => 'name'),
			array(
				'name' => 'is_released',
				'value' => ($category->is_released ? '是' : '否')
			)
		)
	));	?>
	<?php $basicContent = ob_get_clean(); ?>
	<?php $tabs = array('基本资料' => $basicContent); ?>

	 <?php foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) { ?>
	 <?php ob_start(); ?>
	 <?php $this->widget('zii.widgets.CDetailView', array(
		'htmlOptions' => array('class' => 'form'),
		'data' => $category,
		'attributes' => array(
			array(
				'name' => 'name',
				'value' => $category->i18nFormData['name_' . $lang]
			),
			
			array(
				'name' => 'is_released',
				'value' => ($category->i18nFormData['is_released_' . $lang] ? '是' : '否')
			)
		)
	));	?>
	<?php $i18nContent = ob_get_clean(); ?>
	<?php $tabs += array($attr['label'] => $i18nContent); ?>
	<?php } ?>

	<?php $this->widget('zii.widgets.jui.CJuiTabs', array('tabs' => $tabs)); ?>

</div>
