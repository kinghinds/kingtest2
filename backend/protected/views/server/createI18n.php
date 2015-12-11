<table width="100%" class="form">
	<tr>
		<th width="160">
			<?php echo CHtml::activeLabelEx($server, 'name',
		array(
				'for' => CHtml::activeId($server,
						'i18nFormData[name_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::textField(
		CHtml::activeName($server, 'i18nFormData') . '[name_' . $lang . ']',
		$server->i18nFormData['name_' . $lang], array(
				'size' => 80
		));
			?>
		</td>
	</tr>

	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($server, 'image_path',
		array(
				'for' => CHtml::activeId($server,
						'i18nFormData[serverFile_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::fileField(
		CHtml::activeName($server, 'i18nFormData') . '[serverFile_' . $lang
				. ']');
			?>
			<?php if ($server->{'image_path_' . $lang}) { ?> 
			<br />
			<?php if (FileHelper::getFileExt($server->{'image_path_' . $lang})
			== 'swf') {
			?>
			<?php echo CHtml::link(
				Helper::mediaUrl(
						Server::UPLOAD_ORIGINAL_FILE_PATH
								. $server->i18nFormData['image_path_' . $lang],
						FRONTEND),
				Helper::mediaUrl(
						Server::UPLOAD_ORIGINAL_FILE_PATH
								. $server->i18nFormData['image_path_' . $lang],
						FRONTEND), array(
						'target' => '_blank'
				));
			?>
			<?php } else { ?> 
			<?php echo CHtml::image(
				Helper::mediaUrl(
						Server::UPLOAD_ORIGINAL_FILE_PATH
								. $server->i18nFormData['image_path_' . $lang],
						FRONTEND), '', array('style' => 'width:300px;'));
			?>
			<?php } ?> 
			<br />
			<?php echo CHtml::checkBox(
			CHtml::activeName($server, 'i18nFormData') . '[deleteServerFile_'
					. $lang . ']',
			$server->i18nFormData['deleteServerFile_' . $lang]);
			?>
			删除文件 
			<?php } ?> 
			<?php echo Helper::fieldTips('文件尺寸宽 350 像素、 高 230 像素'); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($server, 'sub_content',
		array(
				'for' => CHtml::activeId($server,
						'i18nFormData[sub_content_' . $lang . ']')));
			?>
		</th>
		<td>
			<?php echo CHtml::textArea(
		CHtml::activeName($server, 'i18nFormData') . '[sub_content_'
				. $lang . ']',
		$server->i18nFormData['sub_content_' . $lang],
		array('cols' => 60, 'rows' => 5));
			?>
		</td>
	</tr>
	
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($server, 'content',
		array(
				'for' => CHtml::activeId($server,
						'i18nFormData[content_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php $this->widget('ext.tinymce.TinyMCEWidget',
		array(
				'name' => CHtml::activeName($server, 'i18nFormData')
						. '[content_' . $lang . ']',
				'value' => $server->i18nFormData['content_' . $lang],
				'htmlOptions' => array('cols' => 30, 'rows' => 10)
		));
			?>
			<?php echo Helper::fieldTips('输入 {gallery}, 该位置将会被替换成图库'); ?>
			<?php echo CHtml::tag('div', array(), CHtml::link('移除/加载编辑器', '#', array('class' => 'toggle-tinymce-editor'))); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($server, 'is_released',
		array(
				'for' => CHtml::activeId($server,
						'i18nFormData[is_released_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::hiddenField(
		CHtml::activeName($server, 'i18nFormData') . '[is_released_' . $lang
				. ']', 0, array(
				'id' => false
		));
			?>
			<?php echo CHtml::checkBox(
		CHtml::activeName($server, 'i18nFormData') . '[is_released_' . $lang
				. ']', $server->i18nFormData['is_released_' . $lang]);
			?>
		</td>
	</tr>
</table>