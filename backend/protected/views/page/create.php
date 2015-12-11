<?php $this->beginClip('extraHead'); ?>
<script type="text/javascript">
<!--
var moduleSet = <?php echo CJavaScript::encode(Page::$moduleSet); ?>;
$(function() {
	$('#Page_module_name').bind('change', function() {
		var module = $(this).val();
		if (moduleSet[module].templates) {
			var s = '';
			for (var i in moduleSet[module].templates) {
				s += '<option value="' + i + '">' + moduleSet[module].templates[i] + '</option>';
			}
			$('#Page_module_template').html(s);
			$('#moduleExtInfWrap').show();
		} else {
			$('#moduleExtInfWrap').hide();
		}
	});
});
//-->
</script>
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
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?><?php echo $page->isNewRecord ? '添加' : '修改'; ?>页面</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button', 
				'onclick' => "$('#form1').submit();return false;")); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>

<div class="content">
	<?php echo CHtml::errorSummary($page);	?>

	<?php echo CHtml::form(
		($page->isNewRecord ? array('') : array('', 'id' => $page->primaryKey, 'return_url' => $returnUrl)),
		'post', array(
		'id' => 'form1', 
		'enctype' => 'multipart/form-data'
	));	?>

	<?php ob_start(); ?>
	<table class="form" width="100%">	
		<tr>
			<th width="160"><?php echo CHtml::activeLabelEx($page, 'title'); ?></th>
			<td>
				<?php echo CHtml::activeTextField($page, 'title', array('size' => 80)); ?>
			</td>
		</tr>
		<?php if($viewId !=1) { ?>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'parent_id'); ?></th>
			<td>
				<?php echo CHtml::activeDropDownList($page, 'parent_id', $pageOptions, array('empty' => array('0' => '根目录'))); ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'module_name'); ?></th>
			<td>
				<?php echo CHtml::activeDropDownList($page, 'module_name', $moduleOptions); ?>
				<?php if ($page->module_name && empty(Page::$moduleSet[$page->module_name]['templates']) == false) { ?>
				<?php echo '<div id="moduleExtInfWrap" style="margin-top: 10px;">'; ?>
				<?php echo CHtml::activeLabelEx($page, 'module_template') . ' '; ?>
				<?php echo CHtml::activeDropDownList($page, 'module_template',	Page::getModuleTemplateOptions($page->module_name)); ?>
				<?php echo '</div>'; ?>
				<?php } else { ?>
				<?php echo '<div id="moduleExtInfWrap" style="margin-top: 10px; display: none;">'; ?>
				<?php echo CHtml::activeLabelEx($page, 'module_template') . ' '; ?>
				<?php echo CHtml::activeDropDownList($page, 'module_template', array()); ?>
				<?php echo '</div>'; ?>
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'content'); ?></th>
			<td>
				<?php $this->widget('ext.tinymce.TinyMCEWidget', array('model' => $page, 'attribute' => 'content', 'htmlOptions' => array('cols' => 80, 'rows' => 10))); ?>
				<?php echo CHtml::tag('div', array(), CHtml::link('移除/加载编辑器', '#', array('class' => 'toggle-tinymce-editor'))); ?>
			</td>
		</tr>
		<?php if($viewId !=1) { ?>
		<tr>
			<th><?php echo CHtml::activeLabel($page, 'bg_image_path'); ?></th>
			<td>
				<?php echo CHtml::activeFileField($page, 'bgImageFile'); ?> 
				<?php if ($page->bg_image_path) { ?>
				<br />
				<?php echo CHtml::image($page->getBgImageUrl(), '', array('style' => 'width:200px;')); ?>
				<br />
				<?php echo CHtml::activeCheckBox($page, 'deleteBgImageFile'); ?> 删除文件
				<br />
				<?php } ?> 
				<?php echo Helper::fieldTips('图片宽 1600 像素、高 240 像素'); ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'internal_link_keywords'); ?></th>
			<td>
				<?php echo CHtml::activeTextField($page, 'internal_link_keywords', 
						array('size' => 80)); ?><br />
				<?php echo CHtml::activeCheckBox($page, 'is_undisplay_ilk'); ?>
				<?php echo CHtml::activeLabelEx($page, 'is_undisplay_ilk'); ?><br />
				<?php echo Helper::fieldTips('使用逗号 , 分隔关键词'); ?>
				<?php echo Helper::fieldTips('如果其他页面出现此关键词，其字符将被自动替换成本页面链接'); ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'search_keywords'); ?></th>
			<td>
				<?php echo CHtml::activeTextField($page, 'search_keywords', 
						array('size' => 80)); ?>
				<?php echo Helper::fieldTips('使用逗号 , 分隔关键词'); ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'slug'); ?></th>
			<td>
				<?php echo CHtml::activeTextField($page, 'slug', array('size' => 80));	?>
				<?php echo Helper::fieldTips('输入 test demo, 将会生成 ' . Yii::app()->request->hostInfo 
					. Yii::app()->params['frontendBaseUrl'] . '/page/view/test-demo.html 固定链接'); ?>
				<?php echo Helper::fieldTips('字符包含为字母A至Z（大小写）、数字0至9、横线-、下横线_，其他字符将被替换为横线');	?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'head_title'); ?></th>
			<td>
				<?php echo CHtml::activeTextField($page, 'head_title', array('size' => 80)); ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'meta_keywords'); ?></th>
			<td>
				<?php echo CHtml::activeTextArea($page, 'meta_keywords', array('cols' => 60, 'rows' => 5)); ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'meta_description'); ?></th>
			<td>
				<?php echo CHtml::activeTextArea($page, 'meta_description', array('cols' => 60, 'rows' => 5));	?>
			</td>
		</tr>			
		<tr style="display:none;">
			<th><?php echo CHtml::activeLabelEx($page, 'banner_section'); ?></th>
			<td>
				<?php echo CHtml::activeTextArea($page, 'banner_section', array('cols' => 60, 'rows' => 5)); ?>
			</td>
		</tr>
		<tr style="display:none;">
			<th><?php echo CHtml::activeLabelEx($page, 'bannerFile'); ?></th>
			<td>
				<?php echo CHtml::activeFileField($page, 'bannerFile'); ?>
				<?php echo Helper::fieldTips('允许上传格式有 swf (Flash), jpg,jpeg,gif,png (图片)<br />上传成功后, 将会直接更改 Banner 代码'); ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'link_url'); ?></th>
			<td>
				<?php echo CHtml::activeTextField($page, 'link_url', array('size' => 80));	?>
				<?php echo CHtml::activeDropDownList($page, 'target_window', $targetWindowOptions); ?>
			</td>
		</tr>
		<?php if($viewId !=1) { ?>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'is_indexable'); ?></th>
			<td><?php echo CHtml::activeCheckBox($page, 'is_indexable'); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'is_released'); ?></th>
			<td><?php echo CHtml::activeCheckBox($page, 'is_released'); ?></td>
		</tr>
	
	</table>
	<?php $basicContent = ob_get_clean(); ?>

	<?php 
		$i18nTabs = array();
		foreach (I18nHelper::getFrontendLanguages() as $lang => $prop) {
			$i18nTabs[$prop['label']] = $this->renderPartial('createI18n', array(
				'page' => $page, 
				'lang' => $lang, 
				'prop' => $prop,
				'targetWindowOptions' => $targetWindowOptions),
				true
			);
		}
	?>

	<?php $this->widget('zii.widgets.jui.CJuiTabs',	array(
		'tabs' => array('基本资料' => $basicContent) + $i18nTabs
	)); ?>

	<?php echo CHtml::endForm(); ?>
</div>
