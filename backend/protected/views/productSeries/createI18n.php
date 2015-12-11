<table width="100%" class="form">
	<tr>
		<th width="160">
			<?php echo CHtml::activeLabelEx($series, 'title',
		array(
				'for' => CHtml::activeId($series,
						'i18nFormData[title_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::textField(
		CHtml::activeName($series, 'i18nFormData') . '[title_' . $lang . ']',
		$series->i18nFormData['title_' . $lang], array(
				'size' => 80
		));
			?>
		</td>
	</tr>

	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($series, 'norms',
		array(
				'for' => CHtml::activeId($series,
						'i18nFormData[norms_' . $lang . ']')));
			?>
		</th>
		<td>
			<?php echo CHtml::textArea(
		CHtml::activeName($series, 'i18nFormData') . '[norms_'
				. $lang . ']',
		$series->i18nFormData['norms_' . $lang],
		array('cols' => 60, 'rows' => 5));
			?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($series, 'series_model',
		array(
				'for' => CHtml::activeId($series,
						'i18nFormData[series_model_' . $lang . ']')));
			?>
		</th>
		<td>
			<?php echo CHtml::textArea(
		CHtml::activeName($series, 'i18nFormData') . '[series_model_'
				. $lang . ']',
		$series->i18nFormData['series_model_' . $lang],
		array('cols' => 60, 'rows' => 5));
			?>
		</td>
	</tr>
	
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($series, 'content',
		array(
				'for' => CHtml::activeId($series,
						'i18nFormData[content_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php $this->widget('ext.tinymce.TinyMCEWidget',
		array(
				'name' => CHtml::activeName($series, 'i18nFormData')
						. '[content_' . $lang . ']',
				'value' => $series->i18nFormData['content_' . $lang],
				'htmlOptions' => array('cols' => 30, 'rows' => 10)
		));
			?>
			<?php echo CHtml::tag('div', array(), CHtml::link('移除/加载编辑器', '#', array('class' => 'toggle-tinymce-editor'))); ?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($series, 'is_released',
		array(
				'for' => CHtml::activeId($series,
						'i18nFormData[is_released_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::hiddenField(
		CHtml::activeName($series, 'i18nFormData') . '[is_released_' . $lang
				. ']', 0, array(
				'id' => false
		));
			?>
			<?php echo CHtml::checkBox(
		CHtml::activeName($series, 'i18nFormData') . '[is_released_' . $lang
				. ']', $series->i18nFormData['is_released_' . $lang]);
			?>
		</td>
	</tr>
</table>