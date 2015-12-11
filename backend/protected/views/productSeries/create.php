<?php $this->beginClip('extraHead'); ?>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl . '/javascript/common.js') . "\r\n"; ?>
<script type="text/javascript">
<!--
$(function() {	
	<?php echo 'var brand_str = \''.json_encode($brandOptions).'\';';?>
	$('.toggle-tinymce-editor').click(function(){
		var tinymceId = $(this).parent().parent().find('.tinymce-editor').attr('id');
		if (tinymceId) {
			$(this).toggleClass('is-removed');
			var action = $(this).hasClass('is-removed') ? 'mceRemoveControl' : 'mceAddControl';
			tinymce.EditorManager.execCommand(action, true, tinymceId);	
		}
		return false;
	});
	var sbrandid = "<?php echo $series->brand_id; ?>";
	var selstr = "";
	// 初始化品牌
	var brand_arr = jQuery.parseJSON(brand_str);
	var _brandidarr = $("#category").find("option[value='"+$("#category").val()+"']").attr('data-spec').split(',');
	$("#brand_id").empty();
	var html = '';
	for(var i=0; i<_brandidarr.length; i++){
		if(sbrandid == _brandidarr[i]){
			html +='<option selected value="'+_brandidarr[i]+'">'+brand_arr[_brandidarr[i]]+'</option>';
		}else{
			html +='<option value="'+_brandidarr[i]+'">'+brand_arr[_brandidarr[i]]+'</option>';
		}
		
	};
	$("#brand_id").append(html);

	$("#category").change(function(event) {
		_brandidarr = $("#category").find("option[value='"+$(this).val()+"']").attr('data-spec').split(',');
		$("#brand_id").empty();
		var html = '';
		for(var i=0; i<_brandidarr.length; i++){
			html +='<option value="'+_brandidarr[i]+'">'+brand_arr[_brandidarr[i]]+'</option>';
		};
		$("#brand_id").append(html);
	});
	
});
//-->
</script>
<?php $this->endClip(); ?>
<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?><?php echo $series
		->isNewRecord ? '添加' : '修改'; ?>产品系列</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button',
				'onclick' => "$('#form1').submit();return false;")); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>
<div class="content">
	<?php echo CHtml::errorSummary($series); ?>
	
	<?php echo CHtml::form(
		$series->getIsNewRecord() ? array('')
				: array('', 'id' => $series->primaryKey, 'return_url' => $returnUrl), 'post',
		array('id' => 'form1', 'enctype' => 'multipart/form-data'));
	?>
	<?php ob_start(); ?>
	<table class="form" width="100%">
		<tr>
			<th width="160"><?php echo CHtml::activeLabelEx($series, 'title'); ?></th>
			<td><?php echo CHtml::activeTextField($series, 'title',array('size' => 80));?></td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($series, 'category_id'); ?>
			</th>
			<td>
				<select name="ProductSeries[category_id]" id="category">
					<?php if(!empty($productCategoryOptions)){
						foreach ($productCategoryOptions as $key => $value) {?>
						<option data-spec="<?php echo $value['brand_id'];?>" <?php if (!empty($value['child'])) echo 'disabled="disabled" style="color:gray;"'?> value="<?php echo $value['category_id'];?>" <?php if(isset($series['category_id']) && $series['category_id'] == $value['category_id']) echo 'selected'; ?>><?php echo $value['name'];?></option>
						<?php if (!empty($value['child'])) {
						foreach ($value['child'] as $k => $v) {?>
						<option data-spec="<?php echo $value['brand_id'];?>" value="<?php echo $v['category_id'];?>" <?php if(isset($series['category_id']) && $series['category_id'] == $v['category_id']) echo 'selected'; ?>>&nbsp;&nbsp;&nbsp;┣━<?php echo $v['name'];?></option>
					<?php }}}} ?>	
				</select>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($series, 'brand_id'); ?>
			</th>
			<td>
				<select name="ProductSeries[brand_id]" id="brand_id">
					<option value="0">请选择</option>
					<?php foreach ($brandOptions as $key => $value): ?>
					<option value="<?php echo $key; ?>" <?php if (!empty($series) && $series->brand_id == $key) {
						echo 'selected="selected"';
					} ?>><?php echo $value; ?></option>	
					<?php endforeach ?>
				</select>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($series, 'norms'); ?></th>
			<td>
				<?php echo CHtml::activeTextArea($series, 'norms',array('cols' => 60, 'rows' => 5));
				?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($series, 'series_model'); ?></th>
			<td>
				<?php echo CHtml::activeTextArea($series, 'series_model',array('cols' => 60, 'rows' => 5));
				?>
			</td>
		</tr>

		<tr>
			<th><?php echo CHtml::activeLabelEx($series, 'content'); ?></th>
			<td>
				<?php $this
			->widget('ext.tinymce.TinyMCEWidget',
					array('model' => $series, 'attribute' => 'content', 'htmlOptions' => array('cols' => 30, 'rows' => 10)));
				?>
				<?php echo CHtml::tag('div', array(), CHtml::link('移除/加载编辑器', '#', array('class' => 'toggle-tinymce-editor'))); ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($series, 'is_released'); ?></th>
			<td><?php echo CHtml::activeCheckBox($series, 'is_released'); ?></td>
		</tr>
	</table>
	<?php
$basicContent = ob_get_clean();
$i18nTabs = array();
$tabs = array();
foreach (I18nHelper::getFrontendLanguages() as $lang => $prop) {
	$i18nTabs[$prop['label']] = $this
			->renderPartial('createI18n',
					array('series' => $series, 'lang' => $lang, 'prop' => $prop),
					true);
}
$this
		->widget('zii.widgets.jui.CJuiTabs',
				array('tabs' => array('基本资料' => $basicContent) + $i18nTabs));
	?>
	<?php echo CHtml::endForm(); ?>
</div>