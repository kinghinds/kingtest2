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
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?><?php echo $product
		->isNewRecord ? '添加' : '修改';?>产品</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button',
				'onclick' => "$('#form1').submit();return false;")); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>
<div class="content">
	<?php echo CHtml::errorSummary($product); ?>
	
	<?php echo CHtml::form(
		$product->getIsNewRecord() ? array('')
				: array('', 'id' => $product->primaryKey, 'return_url' => $returnUrl), 'post',
		array('id' => 'form1', 'enctype' => 'multipart/form-data'));
	?>
	<?php ob_start(); ?>
	<table class="form" width="100%">
		<tr>
			<th width="160"><?php echo CHtml::activeLabelEx($product, 'name'); ?></th>
			<td><?php echo CHtml::activeTextField($product, 'name',
		array('size' => 80));
				?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($product, 'series_id'); ?>
			</th>
			<td>
				<select name="Product[series_id]" id="series">
					<?php if(!empty($productSeriesOptions)){
						foreach ($productSeriesOptions as $key => $value) {?>
						<option value="<?php echo $key;?>" <?php if(isset($product['series_id']) && $product['series_id'] == $key) echo 'selected'; ?>><?php echo $value;?></option>
					<?php }} ?>	
				</select>
			</td>
		</tr>
		<!-- <tr>
			<th><?php echo CHtml::activeLabelEx($product, 'category_id'); ?>
			</th>
			<td>
				<select name="Product[category_id]" id="category">
					<?php if(!empty($productCategoryOptions)){
						foreach ($productCategoryOptions as $key => $value) {?>
						<option data-spec="<?php echo $value['category_id'];?>" <?php if (!empty($value['child'])) echo 'disabled="disabled" style="color:gray;"'?> value="<?php echo $value['category_id'];?>" <?php if(isset($product['parent_id']) && $product['parent_id'] == $value['category_id']) echo 'selected'; ?>><?php echo $value['name'];?></option>
						<?php if (!empty($value['child'])) {
						foreach ($value['child'] as $k => $v) {?>
						<option value="<?php echo $v['category_id'];?>">&nbsp;&nbsp;&nbsp;┣━<?php echo $v['name'];?></option>
					<?php }}}} ?>	
				</select>
			</td>
		</tr> -->

		<tr>
			<th><?php echo CHtml::activeLabel($product, 'image_path'); ?></th>
			<td>
				<?php echo CHtml::activeFileField($product, 'productFile'); ?> 
				<?php if ($product->image_path) { ?>
				<br />
				<?php if (FileHelper::getExtension($product->image_path)
			== 'swf') {
				?>
				<?php echo CHtml::link($product->getProductFileUrl(),
				$product->getProductFileUrl(), array('target' => '_blank'));
				?>
				<?php } else { ?>
				<?php echo CHtml::image($product->getProductFileUrl(), '', 
						array('style' => 'width:300px;')); ?>
				<?php } ?>
				<br />
				<?php echo CHtml::activeCheckBox($product, 'deleteProductFile'); ?> 删除文件
				<br />
				<?php } ?> 
				<?php echo Helper::fieldTips('文件尺寸宽 350 像素、 高 230 像素'); ?>
			</td>
		</tr>
		<!-- <tr>
			<th><?php echo CHtml::activeLabelEx($product, 'sub_content'); ?></th>
			<td>
				<?php echo CHtml::activeTextArea($product, 'sub_content',
		array('cols' => 60, 'rows' => 5));
				?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($product, 'content'); ?></th>
			<td>
				<?php $this
			->widget('ext.tinymce.TinyMCEWidget',
					array('model' => $product, 'attribute' => 'content', 'htmlOptions' => array('cols' => 30, 'rows' => 10)));
				?>
				<?php echo CHtml::tag('div', array(), CHtml::link('移除/加载编辑器', '#', array('class' => 'toggle-tinymce-editor'))); ?>
			</td>
		</tr> -->
		<tr>
			<th><?php echo CHtml::activeLabelEx($product, 'is_recommend'); ?></th>
			<td><?php echo CHtml::activeCheckBox($product, 'is_recommend'); ?></td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($product, 'is_released'); ?></th>
			<td><?php echo CHtml::activeCheckBox($product, 'is_released'); ?></td>
		</tr>
	</table>
	<?php
$basicContent = ob_get_clean();
$i18nTabs = array();
$tabs = array();
foreach (I18nHelper::getFrontendLanguages() as $lang => $prop) {
	$i18nTabs[$prop['label']] = $this
			->renderPartial('createI18n',
					array('product' => $product, 'lang' => $lang, 'prop' => $prop),
					true);
}
$this->widget('zii.widgets.jui.CJuiTabs',
				array(
						'tabs' => array('基本资料' => $basicContent) + $i18nTabs
								// + array(
								// 		'图集' => $this
								// 				->renderPartial('createImage',
								// 						array(
								// 								'product' => $product,
								// 								'imageList' => $imageList),
								// 						true))
								)
				);
	?>
	<?php echo CHtml::endForm(); ?>
</div>