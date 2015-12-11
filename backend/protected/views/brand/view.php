<?php $this->beginClip('extraHead'); ?>
<?php Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl()
			. '/jui/css/base/jquery-ui.css'); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php $this->endClip(); ?>

<div class="heading">
	<h1>
		<?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>
		查看Banner
	</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('updateBanner')) { ?>
		<?php echo CHtml::link('<span>修改</span>', array('update', 
				'id' => $banner->banner_id, 'return_url' => $returnUrl), array(
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
		'data' => $banner,
		'attributes' => array(
			array('name' => 'title'),
			array(
				'name' => 'banner_path',
				'value' => CHtml::image($banner->getBannerFileUrl(), '', array('style' => 'width:300px;')),
				'type' => 'html',
				'visible' => (
					strlen($banner->banner_path) > 0
					&& in_array(FileHelper::getExtension($banner->banner_path), array('jpg', 'jpeg', 'gif', 'png'))
				)
			),
			array(
				'name' => 'banner_path',
				'value' => CHtml::link($banner->banner_path, $banner->getBannerFileUrl()),
				'type' => 'html',
				'visible' => (
					strlen($banner->banner_path) > 0
					&& in_array(FileHelper::getExtension($banner->banner_path), array('jpg', 'jpeg', 'gif', 'png')) == false
				)
			),
			array('name' => 'link_url'),
			array(
				'name' => 'is_released',
				'value' => ($banner->is_released ? '是' : '否')
			)
		)
	));	?>
	<?php $basicContent = ob_get_clean(); ?>
	<?php $tabs = array('基本资料' => $basicContent); ?>

	 <?php foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) { ?>
	 <?php ob_start(); ?>
	 <?php $this->widget('zii.widgets.CDetailView', array(
		'htmlOptions' => array('class' => 'form'),
		'data' => $banner,
		'attributes' => array(
			array(
				'name' => 'title',
				'value' => $banner->i18nFormData['title_' . $lang]
			),
			array(
				'name' => 'banner_path',
				'value' => CHtml::image(
					Helper::mediaUrl(Banner::UPLOAD_THUMBNAIL_IMAGE_PATH . $banner->i18nFormData['banner_path_' . $lang])
				),
				'type' => 'html',
				'visible' => (
					strlen($banner->i18nFormData['banner_path_' . $lang]) > 0
					&& in_array(FileHelper::getExtension($banner->i18nFormData['banner_path_' . $lang]), array('jpg', 'jpeg', 'gif', 'png'))
				)
			),
			array(
				'name' => 'banner_path',
				'value' => CHtml::link(
					$banner->i18nFormData['banner_path_' . $lang],
					Helper::mediaUrl(Banner::UPLOAD_THUMBNAIL_IMAGE_PATH . $banner->i18nFormData['banner_path_' . $lang])
				),
				'type' => 'html',
				'visible' => (
					strlen(Banner::UPLOAD_THUMBNAIL_IMAGE_PATH . $banner->i18nFormData['banner_path_' . $lang]) > 0
					&& in_array(FileHelper::getExtension(Banner::UPLOAD_THUMBNAIL_IMAGE_PATH . $banner->i18nFormData['banner_path_' . $lang]), array('jpg', 'jpeg', 'gif', 'png')) == false
				)
			),
			array(
				'name' => 'is_released',
				'value' => ($banner->i18nFormData['is_released_' . $lang] ? '是' : '否')
			)
		)
	));	?>
	<?php $i18nContent = ob_get_clean(); ?>
	<?php $tabs += array($attr['label'] => $i18nContent); ?>
	<?php } ?>

	<?php $this->widget('zii.widgets.jui.CJuiTabs', array('tabs' => $tabs)); ?>

</div>
