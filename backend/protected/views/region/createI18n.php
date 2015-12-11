<table width="100%" class="form">
	<tr>
		<th width="160">
			<?php echo CHtml::activeLabelEx($data, 'title',
		array(
				'for' => CHtml::activeId($data,
						'i18nFormData[title_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::textField(
		CHtml::activeName($data, 'i18nFormData') . '[title_' . $lang . ']',
		$data->i18nFormData['title_' . $lang], array(
				'size' => 80
		));
			?>
		</td>
	</tr>

	
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($data, 'is_released',
		array(
				'for' => CHtml::activeId($data,
						'i18nFormData[is_released_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::hiddenField(
		CHtml::activeName($data, 'i18nFormData') . '[is_released_' . $lang
				. ']', 0, array(
				'id' => false
		));
			?>
			<?php echo CHtml::checkBox(
		CHtml::activeName($data, 'i18nFormData') . '[is_released_' . $lang
				. ']', $data->i18nFormData['is_released_' . $lang]);
			?>
		</td>
	</tr>
</table>