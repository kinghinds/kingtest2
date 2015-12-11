<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?><?php echo $model
		->isNewRecord ? '添加' : '修改';
																			   ?>Banner</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#',
		array('class' => 'button',
				'onclick' => "$('#form1').submit();return false;"));
		?>
		<?php echo CHtml::link('<span>取消</span>', array('index'),
		array('class' => 'button'));
		?>
	</div>
</div>
<div class="content">
	<?php if ($model->hasErrors())
	echo CHtml::errorSummary($model);
	?>
	
	<?php echo CHtml::form(
		$model->getIsNewRecord() ? array('')
				: array('', 'id' => $model->primaryKey), 'post',
		array('id' => 'form1', 'enctype' => 'multipart/form-data'));
	?>
	<?php ob_start(); ?>
	<table class="form" width="100%">
		<tr>
			<th width="160"><?php echo CHtml::activeLabelEx($model, 'title'); ?></th>
			<td><?php echo CHtml::activeTextField($model, 'title',
		array('size' => 80));
				?>
			</td>
		</tr>
		
		<tr>
			<th><?php echo CHtml::activeLabelEx($model, 'banner_position_id'); ?></th>
			<td><?php echo CHtml::activeDropDownList($model,
		'banner_position_id', $bannerPositionOptions);
				?>
			</td>
		</tr>
		
		<tr>
			<th><?php echo CHtml::activeLabel($model, 'banner_path'); ?></th>
			<td>
				<?php echo CHtml::activeFileField($model, 'bannerFile'); ?> 
				<?php if ($model->banner_path) { ?>
				<br />
				<?php if (FileHelper::getExtension($model->banner_path)
			== 'swf') {
				?>
				<?php echo CHtml::link($model->getLargeUrl(),
				$model->getBannerUrl(), array('target' => '_blank'));
				?>
				<?php } else { ?>
				<?php echo CHtml::image($model->getLargeUrl());
				?>
				<?php } ?>
				<br />
				<?php echo CHtml::activeCheckBox($model, 'deleteBannerFile'); ?> 删除文件
				<br />
				<?php } ?> 
				<?php echo Helper::fieldTips('文件尺寸宽 1200 像素、 高 200 像素'); ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($model, 'sub_content'); ?></th>
			<td>
				<?php echo CHtml::activeTextArea($model, 'sub_content',
		array('cols' => 60, 'rows' => 5));
				?>
			</td>
		</tr>
		
		<tr>
			<th><?php echo CHtml::activeLabelEx($model, 'is_released'); ?></th>
			<td><?php echo CHtml::activeCheckBox($model, 'is_released'); ?></td>
		</tr>
	</table>
	<?php
$basicContent = ob_get_clean();
$i18nTabs = array();
$tabs = array();
foreach (I18nHelper::getFrontendLanguages() as $lang => $prop) {
	$i18nTabs[$prop['label']] = $this
			->renderPartial('createI18n',
					array('model' => $model, 'lang' => $lang, 'prop' => $prop),
					true);
}
$this
		->widget('zii.widgets.jui.CJuiTabs',
				array('tabs' => array('基本资料' => $basicContent) + $i18nTabs));
	?>
	<?php echo CHtml::endForm(); ?>
</div>