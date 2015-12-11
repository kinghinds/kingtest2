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
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?><?php echo $data
		->isNewRecord ? '添加' : '修改';
																			   ?>品牌地区</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button',
				'onclick' => "$('#form1').submit();return false;")); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>
<div class="content">
	<?php echo CHtml::errorSummary($data); ?>
	
	<?php echo CHtml::form(
		$data->getIsNewRecord() ? array('')
				: array('', 'id' => $data->primaryKey, 'return_url' => $returnUrl), 'post',
		array('id' => 'form1', 'enctype' => 'multipart/form-data'));
	?>
	<?php ob_start(); ?>
	<table class="form" width="100%">
		<tr>
			<th width="160"><?php echo CHtml::activeLabelEx($data, 'title'); ?></th>
			<td><?php echo CHtml::activeTextField($data, 'title',
		array('size' => 80));
				?>
			</td>
		</tr>
		
		<tr>
			<th><?php echo CHtml::activeLabelEx($data, 'is_released'); ?></th>
			<td><?php echo CHtml::activeCheckBox($data, 'is_released'); ?></td>
		</tr>
	</table>
	<?php
$basicContent = ob_get_clean();
$i18nTabs = array();
$tabs = array();
foreach (I18nHelper::getFrontendLanguages() as $lang => $prop) {
	$i18nTabs[$prop['label']] = $this
			->renderPartial('createI18n',
					array('data' => $data, 'lang' => $lang, 'prop' => $prop),
					true);
}
$this
		->widget('zii.widgets.jui.CJuiTabs',
				array('tabs' => array('基本资料' => $basicContent) + $i18nTabs));
	?>
	<?php echo CHtml::endForm(); ?>
</div>