<table width="100%" class="form">
	<tr>
		<th width="160">
			<?php echo CHtml::activeLabelEx($model, 'title',
		array(
				'for' => CHtml::activeId($model,
						'i18nFormData[title_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::textField(
		CHtml::activeName($model, 'i18nFormData') . '[title_' . $lang . ']',
		$model->i18nFormData['title_' . $lang], array(
				'size' => 80
		));
			?>
		</td>
	</tr>

	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($model, 'banner_path',
		array(
				'for' => CHtml::activeId($model,
						'i18nFormData[bannerFile_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::fileField(
		CHtml::activeName($model, 'i18nFormData') . '[bannerFile_' . $lang
				. ']');
			?>
			<?php if ($model->{'banner_path_' . $lang}) { ?> 
			<br />
			<?php if (FileHelper::getFileExt($model->{'banner_path_' . $lang})
			== 'swf') {
			?>
			<?php echo CHtml::link(
				Helper::mediaUrl(
						Banner::UPLOAD_ORIGINAL_FILE_PATH
								. $model->i18nFormData['banner_path_' . $lang],
						FRONTEND),
				Helper::mediaUrl(
						Banner::UPLOAD_ORIGINAL_FILE_PATH
								. $model->i18nFormData['banner_path_' . $lang],
						FRONTEND), array(
						'target' => '_blank'
				));
			?>
			<?php } else { ?> 
			<?php echo CHtml::image(
				Helper::mediaUrl(
						Banner::UPLOAD_LARGE_IMAGE_PATH
								. $model->i18nFormData['banner_path_' . $lang],
						FRONTEND));
			?>
			<?php } ?> 
			<br />
			<?php echo CHtml::checkBox(
			CHtml::activeName($model, 'i18nFormData') . '[deleteBannerFile_'
					. $lang . ']',
			$model->i18nFormData['deleteBannerFile_' . $lang]);
			?>
			删除文件 
			<?php } ?> 
			<?php echo Helper::fieldTips('文件尺寸宽 1200 像素、 高 200 像素'); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($model, 'sub_content',
		array(
				'for' => CHtml::activeId($model,
						'i18nFormData[sub_content_' . $lang . ']')));
			?>
		</th>
		<td>
			<?php echo CHtml::textArea(
		CHtml::activeName($model, 'i18nFormData') . '[sub_content_'
				. $lang . ']',
		$model->i18nFormData['sub_content_' . $lang],
		array('cols' => 60, 'rows' => 5));
			?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($model, 'is_released',
		array(
				'for' => CHtml::activeId($model,
						'i18nFormData[is_released_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::hiddenField(
		CHtml::activeName($model, 'i18nFormData') . '[is_released_' . $lang
				. ']', 0, array(
				'id' => false
		));
			?>
			<?php echo CHtml::checkBox(
		CHtml::activeName($model, 'i18nFormData') . '[is_released_' . $lang
				. ']', $model->i18nFormData['is_released_' . $lang]);
			?>
		</td>
	</tr>
</table>