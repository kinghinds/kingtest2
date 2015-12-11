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

	$('.selected-all').click(function(){
		$(this).parent().next().find('.privileges').attr('checked', 'checked');
		return false;
	});

	$('.inverse').click(function(){
		$.each($(this).parent().next().find('.privileges'), function(i, n){
			if ($(n).attr('checked') == true 
					|| $(n).attr('checked') == 'checked') {
				$(n).removeAttr('checked');
			} else {
				$(n).attr('checked', 'checked');
			}
		});
		return false;
	});
	var str = "<?php echo $category->brand_id; ?>"
	var _brandidarr = str.split(',');
	for(var i=0; i<_brandidarr.length; i++){
		$(".optionbox").find("input[value='"+_brandidarr[i]+"']").attr('checked', 'checked');
	};
});
//-->
</script>
<?php $this->endClip(); ?>
<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?><?php echo $category
		->isNewRecord ? '添加' : '修改'; ?>广告</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button',
				'onclick' => "$('#form1').submit();return false;")); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>
<div class="content">
	<?php echo CHtml::errorSummary($category); ?>
	
	<?php echo CHtml::form(
		$category->getIsNewRecord() ? array('')
				: array('', 'id' => $category->primaryKey, 'return_url' => $returnUrl), 'post',
		array('id' => 'form1', 'enctype' => 'multipart/form-data'));
	?>
	<?php ob_start(); ?>
	<table class="form" width="100%">
		<tr>
			<th width="160"><?php echo CHtml::activeLabelEx($category, 'name'); ?></th>
			<td><?php echo CHtml::activeTextField($category, 'name',array('size' => 80));?></td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($category, 'parent_id'); ?>
			</th>
			<td>
				<select name="ProductCategory[parent_id]" id="parent_id">
					<option value="0" data-brand="0">顶级分类</option>
					<?php foreach ($productCategoryOptions as $key => $value): ?>
					<option value="<?php echo $value['category_id']; ?>" <?php if (!empty($category) && $category->parent_id == $value['category_id']) { echo 'selected="selected"';} ?>><?php echo $value['name']; ?></option>	
					<?php endforeach ?>
				</select>
			</td>
		</tr>
		
		<tr>
			<th><?php echo CHtml::activeLabelEx($category, 'brand_id'); ?>
			</th>
			<td>
				<div class="privilege_main">
					<div class="privilege_wide">
						<div class="selectbox" style="margin-bottom:5px;">
							<a href="#" class="selected-all">全选</a> /
							<a href="#" class="inverse">反选</a>
						</div>
						<div class="optionbox">

							<?php echo CHtml::activeCheckBoxList($category,	
									'brand_id', 
									$brandOptions,array('class'=>'privileges')
									
							); ?>
						</div>
					</div>
				</div>

				<!-- <select name="ProductCategory[brand_id]" id="brand_id">
					<option value="0">请选择</option>
					<?php foreach ($brandOptions as $key => $value): ?>
					<option value="<?php echo $key; ?>" <?php if (!empty($category) && $category->brand_id == $key) {
						echo 'selected="selected"';
					} ?>><?php echo $value; ?></option>	
					<?php endforeach ?>
				</select>
				<?php echo Helper::fieldTips('所属品牌不要留空，以免出错'); ?>
				<input type="hidden" name="ProductCategory[brand_id]" value="" disabled="disabled" class="brand_id"> -->
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($category, 'is_released'); ?></th>
			<td><?php echo CHtml::activeCheckBox($category, 'is_released'); ?></td>
		</tr>
	</table>
	<?php
$basicContent = ob_get_clean();
$i18nTabs = array();
$tabs = array();
foreach (I18nHelper::getFrontendLanguages() as $lang => $prop) {
	$i18nTabs[$prop['label']] = $this
			->renderPartial('createI18n',
					array('category' => $category, 'lang' => $lang, 'prop' => $prop),
					true);
}
$this
		->widget('zii.widgets.jui.CJuiTabs',
				array('tabs' => array('基本资料' => $basicContent) + $i18nTabs));
	?>
	<?php echo CHtml::endForm(); ?>
</div>

<script>
	$(function() {
		$('#parent_id').change(function(event) {
			var _brand_id = $(this).find("option[value='"+$(this).val()+"']").attr('data-brand');

			var _brand = $("#brand_id").find("option[value='"+_brand_id+"']");

				if (_brand_id != 0) {
					$("#brand_id").attr('disabled','disabled');
					$('.brand_id').removeAttr('disabled');
					$('.brand_id').val(_brand_id);
				}else{
					$("#brand_id").removeAttr('disabled');
					$(".brand_id").attr('disabled','disabled');
				}

				_brand.attr('selected','selected');
		});
		$(window).load(function() {
			var parent_id = "<?php echo $category->parent_id?>";
			if (parent_id != 0) {
				$("#brand_id").attr('disabled','disabled');
			};
		});
	})
</script>