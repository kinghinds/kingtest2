<table width="100%" class="form">
	<tr>
		<th width="160">
			<?php echo CHtml::activeLabelEx($product, 'name',
		array(
				'for' => CHtml::activeId($product,
						'i18nFormData[name_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::textField(
		CHtml::activeName($product, 'i18nFormData') . '[name_' . $lang . ']',
		$product->i18nFormData['name_' . $lang], array(
				'size' => 80
		));
			?>
		</td>
	</tr>

	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($product, 'image_path',
		array(
				'for' => CHtml::activeId($product,
						'i18nFormData[productFile_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::fileField(
		CHtml::activeName($product, 'i18nFormData') . '[productFile_' . $lang
				. ']');
			?>
			<?php if ($product->{'image_path_' . $lang}) { ?> 
			<br />
			<?php if (FileHelper::getFileExt($product->{'image_path_' . $lang})
			== 'swf') {
			?>
			<?php echo CHtml::link(
				Helper::mediaUrl(
						Product::UPLOAD_ORIGINAL_IMAGE_PATH
								. $product->i18nFormData['image_path_' . $lang],
						FRONTEND),
				Helper::mediaUrl(
						Product::UPLOAD_ORIGINAL_IMAGE_PATH
								. $product->i18nFormData['image_path_' . $lang],
						FRONTEND), array(
						'target' => '_blank'
				));
			?>
			<?php } else { ?> 
			<?php echo CHtml::image(
				Helper::mediaUrl(
						Product::UPLOAD_ORIGINAL_IMAGE_PATH
								. $product->i18nFormData['image_path_' . $lang],
						FRONTEND), '', array('style' => 'width:300px;'));
			?>
			<?php } ?> 
			<br />
			<?php echo CHtml::checkBox(
			CHtml::activeName($product, 'i18nFormData') . '[deleteProductFile_'
					. $lang . ']',
			$product->i18nFormData['deleteProductFile_' . $lang]);
			?>
			删除文件 
			<?php } ?> 
			<?php echo Helper::fieldTips('文件尺寸宽 1600 像素、 高 540 像素'); ?>
		</td>
	</tr>
	<!-- <tr>
		<th>
			<?php echo CHtml::activeLabelEx($product, 'sub_content',
		array(
				'for' => CHtml::activeId($product,
						'i18nFormData[sub_content_' . $lang . ']')));
			?>
		</th>
		<td>
			<?php echo CHtml::textArea(
		CHtml::activeName($product, 'i18nFormData') . '[sub_content_'
				. $lang . ']',
		$product->i18nFormData['sub_content_' . $lang],
		array('cols' => 60, 'rows' => 5));
			?>
		</td>
	</tr>
	
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($product, 'content',
		array(
				'for' => CHtml::activeId($product,
						'i18nFormData[content_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php $this->widget('ext.tinymce.TinyMCEWidget',
		array(
				'name' => CHtml::activeName($product, 'i18nFormData')
						. '[content_' . $lang . ']',
				'value' => $product->i18nFormData['content_' . $lang],
				'htmlOptions' => array('cols' => 30, 'rows' => 10)
		));
			?>
			<?php echo Helper::fieldTips('输入 {gallery}, 该位置将会被替换成图库'); ?>
			<?php echo CHtml::tag('div', array(), CHtml::link('移除/加载编辑器', '#', array('class' => 'toggle-tinymce-editor'))); ?>
		</td>
	</tr> -->
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($product, 'is_released',
		array(
				'for' => CHtml::activeId($product,
						'i18nFormData[is_released_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::hiddenField(
		CHtml::activeName($product, 'i18nFormData') . '[is_released_' . $lang
				. ']', 0, array(
				'id' => false
		));
			?>
			<?php echo CHtml::checkBox(
		CHtml::activeName($product, 'i18nFormData') . '[is_released_' . $lang
				. ']', $product->i18nFormData['is_released_' . $lang]);
			?>
		</td>
	</tr>
</table>