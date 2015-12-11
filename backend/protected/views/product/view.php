<?php $this->beginClip('extraHead'); ?>
<?php Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl()
			. '/jui/css/base/jquery-ui.css'); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php $this->endClip(); ?>

<div class="heading">
	<h1>
		<?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>
		查看产品
	</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('updateProduct')) { ?>
		<?php echo CHtml::link('<span>修改</span>', array('update', 
				'id' => $product->product_id, 'return_url' => $returnUrl), array(
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
		'data' => $product,
		'attributes' => array(
			array('name' => 'name'),
			array(
				'name' => 'image_path',
				'value' => CHtml::image($product->getProductFileUrl(), '', array('style' => 'width:300px;')),
				'type' => 'html',
				'visible' => (
					strlen($product->image_path) > 0
					&& in_array(FileHelper::getExtension($product->image_path), array('jpg', 'jpeg', 'gif', 'png'))
				)
			),
			array(
				'name' => 'image_path',
				'value' => CHtml::link($product->image_path, $product->getProductFileUrl()),
				'type' => 'html',
				'visible' => (
					strlen($product->image_path) > 0
					&& in_array(FileHelper::getExtension($product->image_path), array('jpg', 'jpeg', 'gif', 'png')) == false
				)
			),
			array(
				'name' => 'is_released',
				'value' => ($product->is_released ? '是' : '否')
			)
		)
	));	?>
	<?php $basicContent = ob_get_clean(); ?>
	<?php $tabs = array('基本资料' => $basicContent); ?>

	 <?php foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) { ?>
	 <?php ob_start(); ?>
	 <?php $this->widget('zii.widgets.CDetailView', array(
		'htmlOptions' => array('class' => 'form'),
		'data' => $product,
		'attributes' => array(
			array(
				'name' => 'name',
				'value' => $product->i18nFormData['name_' . $lang]
			),
			array(
				'name' => 'image_path',
				'value' => CHtml::image(
					Helper::mediaUrl(Product::UPLOAD_THUMBNAIL_IMAGE_PATH . $product->i18nFormData['image_path_' . $lang])
				),
				'type' => 'html',
				'visible' => (
					strlen($product->i18nFormData['image_path_' . $lang]) > 0
					&& in_array(FileHelper::getExtension($product->i18nFormData['image_path_' . $lang]), array('jpg', 'jpeg', 'gif', 'png'))
				)
			),
			array(
				'name' => 'image_path',
				'value' => CHtml::link(
					$product->i18nFormData['image_path_' . $lang],
					Helper::mediaUrl(Product::UPLOAD_THUMBNAIL_IMAGE_PATH . $product->i18nFormData['image_path_' . $lang])
				),
				'type' => 'html',
				'visible' => (
					strlen(Product::UPLOAD_THUMBNAIL_IMAGE_PATH . $product->i18nFormData['image_path_' . $lang]) > 0
					&& in_array(FileHelper::getExtension(Product::UPLOAD_THUMBNAIL_IMAGE_PATH . $product->i18nFormData['image_path_' . $lang]), array('jpg', 'jpeg', 'gif', 'png')) == false
				)
			),
			array(
				'name' => 'is_released',
				'value' => ($product->i18nFormData['is_released_' . $lang] ? '是' : '否')
			)
		)
	));	?>
	<?php $i18nContent = ob_get_clean(); ?>
	<?php $tabs += array($attr['label'] => $i18nContent); ?>
	<?php } ?>

	<?php $this->widget('zii.widgets.jui.CJuiTabs', array('tabs' => $tabs)); ?>

</div>
