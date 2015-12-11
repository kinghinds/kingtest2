<table width="100%" class="form">
	<tr>
		<th width="160">
			<?php echo CHtml::activeLabelEx($category, 'name',
		array(
				'for' => CHtml::activeId($category,
						'i18nFormData[name_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::textField(
		CHtml::activeName($category, 'i18nFormData') . '[name_' . $lang . ']',
		$category->i18nFormData['name_' . $lang], array(
				'size' => 80
		));
			?>
		</td>
	</tr>

	
	<tr>
		<th>
			<?php echo CHtml::activeLabelEx($category, 'is_released',
		array(
				'for' => CHtml::activeId($category,
						'i18nFormData[is_released_' . $lang . ']')
		));
			?>
		</th>
		<td>
			<?php echo CHtml::hiddenField(
		CHtml::activeName($category, 'i18nFormData') . '[is_released_' . $lang
				. ']', 0, array(
				'id' => false
		));
			?>
			<?php echo CHtml::checkBox(
		CHtml::activeName($category, 'i18nFormData') . '[is_released_' . $lang
				. ']', $category->i18nFormData['is_released_' . $lang]);
			?>
		</td>
	</tr>
</table>