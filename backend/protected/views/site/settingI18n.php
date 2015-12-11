<table class="form" width="100%">

	<tr>
		<th width="160">首页页面标题</th>
		<td>
			<?php echo CHtml::textField(
		'home_title[i18nFormData][value_' . $lang . ']',
		$models['home_title']['i18nAttributes']['value_' . $lang],
		array('size' => 80));
			?>
		</td>
	</tr>
	<tr>
		<th>内页页面标题</th>
		<td>
			<?php echo CHtml::textField(
		'inside_title[i18nFormData][value_' . $lang . ']',
		$models['inside_title']['i18nAttributes']['value_' . $lang],
		array('size' => 80));
			?>
		</td>
	</tr>
	
	<tr>
		<th>版权信息</th>
		<td>
			<?php echo CHtml::textField(
		'copyright[i18nFormData][value_' . $lang . ']',
		$models['copyright']['i18nAttributes']['value_' . $lang],
		array('size' => 80));
			?>
		</td>
	</tr>
	<tr>
		<th>联系邮箱</th>
		<td>
			<?php echo CHtml::textField(
		'email[i18nFormData][value_' . $lang . ']',
		$models['email']['i18nAttributes']['value_' . $lang],
		array('size' => 80));
			?>
		</td>
	</tr>
	<tr>
		<th>地址信息</th>
		<td>
			<?php $this->widget('ext.tinymce.TinyMCEWidget',
			array(
				'name' => 'address[i18nFormData][value_' . $lang . ']',
				'value' => $models['address']['i18nAttributes']['value_' . $lang],
				'htmlOptions' => array('cols' => 30, 'rows' => 10)
			));
			?>
			
		</td>
	</tr>
	<tr>
		<th>页面关键词</th>
		<td>
			<?php echo CHtml::textArea(
		'meta_keywords[i18nFormData][value_' . $lang . ']',
		$models['meta_keywords']['i18nAttributes']['value_' . $lang],
		array('cols' => 60, 'rows' => 5));
			?>
		</td>
	</tr>

	<tr>
		<th>页面描述</th>
		<td>
			<?php echo CHtml::textArea(
		'meta_description[i18nFormData][value_' . $lang . ']',
		$models['meta_description']['i18nAttributes']['value_' . $lang],
		array('cols' => 60, 'rows' => 5));
			?>
		</td>
	</tr>

</table>
