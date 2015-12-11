<table width="100%" class="form">
	<tr>
		<th width="160">
			<?php echo CHtml::activeLabelEx($page, 'title', array('for' => CHtml::activeId($page,	'i18nFormData[title_' . $lang . ']')));	?>
		</th>
		<td>
			<?php echo CHtml::textField(CHtml::activeName($page, 'i18nFormData') . '[title_' . $lang . ']', $page->i18nFormData['title_' . $lang], array('size' => 80)); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($page, 'content', array('for' => CHtml::activeId($page, 'i18nFormData[content_' . $lang . ']')));	?>
		</th>
		<td>
			<?php $this->widget('ext.tinymce.TinyMCEWidget', array(
				'name' => CHtml::activeName($page, 'i18nFormData') . '[content_' . $lang . ']',
				'value' => $page->i18nFormData['content_' . $lang],
				'htmlOptions' => array('cols' => 80, 'rows' => 10)
			)); ?>
			<?php echo CHtml::tag('div', array(), CHtml::link('移除/加载编辑器', '#', array('class' => 'toggle-tinymce-editor'))); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($page, 'internal_link_keywords', array('for' => CHtml::activeId($page, 'i18nFormData[internal_link_keywords_' . $lang . ']'))); ?>
		</th>
		<td>
			<?php echo CHtml::textField(CHtml::activeName($page, 'i18nFormData') . '[internal_link_keywords_' . $lang . ']', $page->i18nFormData['internal_link_keywords_' . $lang], array('size' => 80)); ?><br />
			<?php echo CHtml::hiddenField(CHtml::activeName($page, 'i18nFormData') . '[is_undisplay_ilk_' . $lang . ']', 0, array('id' => false)); ?>
			<?php echo CHtml::checkBox(CHtml::activeName($page, 'i18nFormData') . '[is_undisplay_ilk_' . $lang . ']', $page->i18nFormData['is_undisplay_ilk_' . $lang]); ?><br />
			<?php echo Helper::fieldTips('使用逗号 , 分隔关键词'); ?>
			<?php echo Helper::fieldTips('如果其他页面出现此唯一关键词，其字符将被自动替换成本页面链接'); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($page, 'search_keywords', array('for' => CHtml::activeId($page, 'i18nFormData[search_keywords_' . $lang . ']'))); ?>
		</th>
		<td>
			<?php echo CHtml::textField(CHtml::activeName($page, 'i18nFormData') . '[search_keywords_' . $lang . ']', $page->i18nFormData['search_keywords_' . $lang], array('size' => 80)); ?>
			<?php echo Helper::fieldTips('使用逗号 , 分隔关键词'); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($page, 'head_title', array('for' => CHtml::activeId($page, 'i18nFormData[head_title_' . $lang . ']'))); ?>
		</th>
		<td>
			<?php echo CHtml::textField(CHtml::activeName($page, 'i18nFormData') . '[head_title_' . $lang . ']', $page->i18nFormData['head_title_' . $lang], array('size' => 80)); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($page, 'meta_keywords', array('for' => CHtml::activeId($page, 'i18nFormData[meta_keywords_' . $lang . ']'))); ?>
		</th>
		<td>
			<?php echo CHtml::textArea(CHtml::activeName($page, 'i18nFormData') . '[meta_keywords_' . $lang . ']', $page->i18nFormData['meta_keywords_' . $lang], array('cols' => 60, 'rows' => 5)); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($page, 'meta_description', array('for' => CHtml::activeId($page,'i18nFormData[meta_description_' . $lang . ']'))); ?>
		</th>
		<td>
			<?php echo CHtml::textArea(CHtml::activeName($page, 'i18nFormData') . '[meta_description_' . $lang . ']', $page->i18nFormData['meta_description_' . $lang], array('cols' => 60, 'rows' => 5)); ?>
		</td>
	</tr>
	<tr style="display:none;">
		<th>
			<?php echo CHtml::activeLabelEx($page, 'banner_section', array('for' => CHtml::activeId($page, 'i18nFormData[banner_section_' . $lang . ']'))); ?>
		</th>
		<td>
			<?php echo CHtml::textArea(CHtml::activeName($page, 'i18nFormData') . '[banner_section_' . $lang . ']', $page->i18nFormData['banner_section_' . $lang], array('cols' => 60, 'rows' => 5)); ?>
		</td>
	</tr>
	<tr style="display:none;">
		<th>
			<?php echo CHtml::activeLabelEx($page, 'bannerFile', array('for' => CHtml::activeId($page, 'i18nFormData[bannerFile_' . $lang . ']'))); ?>
		</th>
		<td>
			<?php echo CHtml::fileField(CHtml::activeName($page, 'i18nFormData') . '[bannerFile_' . $lang . ']', $page->i18nFormData['bannerFile_' . $lang]);	?>
			<?php echo Helper::fieldTips('允许上传格式有 swf (Flash), jpg,jpeg,gif,png (图片)<br />上传成功后, 将会直接更改 Banner 代码'); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($page, 'link_url',	array('for' => CHtml::activeId($page, 'i18nFormData[link_url_' . $lang . ']'))); ?>
		</th>
		<td>
			<?php echo CHtml::textField(CHtml::activeName($page, 'i18nFormData') . '[link_url_' . $lang . ']',	$page->i18nFormData['link_url_' . $lang], array('size' => 80)); ?>
			<?php echo CHtml::dropDownList(
					CHtml::activeName($page, 'i18nFormData') . '[target_window_' . $lang . ']', 
					$page->i18nFormData['target_window_' . $lang], 
					$targetWindowOptions
			); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($page, 'is_released', array('for' => CHtml::activeId($page, 'i18nFormData[is_released_' . $lang . ']')));	?>
		</th>
		<td>
			<?php echo CHtml::hiddenField(CHtml::activeName($page, 'i18nFormData') . '[is_released_' . $lang . ']', 0, array('id' => false)); ?>
			<?php echo CHtml::checkBox(CHtml::activeName($page, 'i18nFormData') . '[is_released_' . $lang . ']', $page->i18nFormData['is_released_' . $lang]); ?>
		</td>
	</tr>
</table>
