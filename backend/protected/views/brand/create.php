<?php $this->beginClip('extraHead'); ?>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl . '/javascript/common.js') . "\r\n"; ?>
<script type="text/javascript">
<!--
$(function() {	
	$('.toggle-tinymce-editor').click(function(){
		var tinymceId = $(this).parent().parent().find('.tinymce-editor').attr('id');
		if (tinymceId) {
			$(this).toggleClass('is-removed');
			var action = $(this).hasClass('is-removed') ? 'mceRemoveControl' : 'mceAddControl';
			tinymce.EditorManager.execCommand(action, true, tinymceId);	
		}
		return false;
	});
});
//-->
</script>
<?php $this->endClip(); ?>
<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?><?php echo $brand
		->isNewRecord ? '添加' : '修改';
																			   ?>品牌</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button',
				'onclick' => "$('#form1').submit();return false;")); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>
<div class="content">
	<?php echo CHtml::errorSummary($brand); ?>
	
	<?php echo CHtml::form(
		$brand->getIsNewRecord() ? array('')
				: array('', 'id' => $brand->primaryKey, 'return_url' => $returnUrl), 'post',
		array('id' => 'form1', 'enctype' => 'multipart/form-data'));
	?>
	<?php ob_start(); ?>
	<table class="form" width="100%">
		<tr>
			<th width="160"><?php echo CHtml::activeLabelEx($brand, 'title'); ?></th>
			<td><?php echo CHtml::activeTextField($brand, 'title',
		array('size' => 80));
				?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($brand, 'region_id'); ?>
			</th>
			<td>
				<?php echo CHtml::activeDropDownList($brand,
		'region_id', $regionOptions,
		array('empty' => '','id'=>'region_id'));
				?>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabel($brand, 'image_path'); ?></th>
			<td>
				<?php echo CHtml::activeFileField($brand, 'brandFile'); ?> 
				<?php if ($brand->image_path) { ?>
				<br />
				<?php if (FileHelper::getExtension($brand->image_path)
			== 'swf') {
				?>
				<?php echo CHtml::link($brand->getThumbFileUrl(),
				$brand->getThumbFileUrl(), array('target' => '_blank'));
				?>
				<?php } else { ?>
				<?php echo CHtml::image($brand->getThumbFileUrl(), '', 
						array('style' => 'width:300px;')); ?>
				<?php } ?>
				<br />
				<?php echo CHtml::activeCheckBox($brand, 'deleteBrandFile'); ?> 删除文件
				<br />
				<?php } ?> 
				<?php echo Helper::fieldTips('文件尺寸宽 350 像素、 高 230 像素'); ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($brand, 'sub_content'); ?></th>
			<td>
				<?php echo CHtml::activeTextArea($brand, 'sub_content',
		array('cols' => 60, 'rows' => 5));
				?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($brand, 'content'); ?></th>
			<td>
				<?php $this
			->widget('ext.tinymce.TinyMCEWidget',
					array('model' => $brand, 'attribute' => 'content', 'htmlOptions' => array('cols' => 30, 'rows' => 10)));
				?>
				<?php echo CHtml::tag('div', array(), CHtml::link('移除/加载编辑器', '#', array('class' => 'toggle-tinymce-editor'))); ?>
			</td>
		</tr>
	
		<tr>
			<th><?php echo CHtml::activeLabelEx($brand, 'is_released'); ?></th>
			<td><?php echo CHtml::activeCheckBox($brand, 'is_released'); ?></td>
		</tr>
	</table>
	<?php
$basicContent = ob_get_clean();
$i18nTabs = array();
$tabs = array();
foreach (I18nHelper::getFrontendLanguages() as $lang => $prop) {
	$i18nTabs[$prop['label']] = $this
			->renderPartial('createI18n',
					array('brand' => $brand, 'lang' => $lang, 'prop' => $prop),
					true);
}
$this
		->widget('zii.widgets.jui.CJuiTabs',
				array('tabs' => array('基本资料' => $basicContent) + $i18nTabs));
	?>
	<?php echo CHtml::endForm(); ?>
</div>