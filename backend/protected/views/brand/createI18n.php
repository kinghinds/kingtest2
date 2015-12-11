<table width="100%" class="form">
	<tr>
		<th width="160">
			<?php echo CHtml::activeLabelEx($brand, 'title',
		array(
				'for' => CHtml::activeId($brand,
						'i18nFormData[title_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::textField(
		CHtml::activeName($brand, 'i18nFormData') . '[title_' . $lang . ']',
		$brand->i18nFormData['title_' . $lang], array(
				'size' => 80
		));
			?>
		</td>
	</tr>

	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($brand, 'image_path',
		array(
				'for' => CHtml::activeId($brand,
						'i18nFormData[brandFile_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::fileField(
		CHtml::activeName($brand, 'i18nFormData') . '[brandFile_' . $lang
				. ']');
			?>
			<?php if ($brand->{'image_path_' . $lang}) { ?> 
			<br />
			<?php if (FileHelper::getFileExt($brand->{'image_path_' . $lang})
			== 'swf') {
			?>
			<?php echo CHtml::link(
				Helper::mediaUrl(
						Brand::UPLOAD_THUMBNAIL_IMAGE_PATH
								. $brand->i18nFormData['image_path_' . $lang],
						FRONTEND),
				Helper::mediaUrl(
						Brand::UPLOAD_THUMBNAIL_IMAGE_PATH
								. $brand->i18nFormData['image_path_' . $lang],
						FRONTEND), array(
						'target' => '_blank'
				));
			?>
			<?php } else { ?> 
			<?php echo CHtml::image(
				Helper::mediaUrl(
						Brand::UPLOAD_THUMBNAIL_IMAGE_PATH
								. $brand->i18nFormData['image_path_' . $lang],
						FRONTEND), '', array('style' => 'width:300px;'));
			?>
			<?php } ?> 
			<br />
			<?php echo CHtml::checkBox(
			CHtml::activeName($brand, 'i18nFormData') . '[deleteBrandFile_'
					. $lang . ']',
			$brand->i18nFormData['deleteBrandFile_' . $lang]);
			?>
			删除文件 
			<?php } ?> 
			<?php echo Helper::fieldTips('文件尺寸宽 350 像素、 高 230 像素'); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($brand, 'sub_content',
		array(
				'for' => CHtml::activeId($brand,
						'i18nFormData[sub_content_' . $lang . ']')));
			?>
		</th>
		<td>
			<?php echo CHtml::textArea(
		CHtml::activeName($brand, 'i18nFormData') . '[sub_content_'
				. $lang . ']',
		$brand->i18nFormData['sub_content_' . $lang],
		array('cols' => 60, 'rows' => 5));
			?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($brand, 'content',
		array(
				'for' => CHtml::activeId($brand,
						'i18nFormData[content_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php $this->widget('ext.tinymce.TinyMCEWidget',
		array(
				'name' => CHtml::activeName($brand, 'i18nFormData')
						. '[content_' . $lang . ']',
				'value' => $brand->i18nFormData['content_' . $lang],
				'htmlOptions' => array('cols' => 30, 'rows' => 10)
		));
			?>
			<?php echo Helper::fieldTips('输入 {gallery}, 该位置将会被替换成图库'); ?>
			<?php echo CHtml::tag('div', array(), CHtml::link('移除/加载编辑器', '#', array('class' => 'toggle-tinymce-editor'))); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($brand, 'is_released',
		array(
				'for' => CHtml::activeId($brand,
						'i18nFormData[is_released_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::hiddenField(
		CHtml::activeName($brand, 'i18nFormData') . '[is_released_' . $lang
				. ']', 0, array(
				'id' => false
		));
			?>
			<?php echo CHtml::checkBox(
		CHtml::activeName($brand, 'i18nFormData') . '[is_released_' . $lang
				. ']', $brand->i18nFormData['is_released_' . $lang]);
			?>
		</td>
	</tr>
</table>