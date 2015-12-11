<table width="100%" class="list" id="image-table"<?php if (count($imageList)
		<= 0)
	echo ' style="display: none;"';
													 ?>>
    <thead>
        <tr>
            <th width="200">缩略图</th>
            <th>文件名</th>
            <th width="80">排序</th>
            <th width="60">显示</th>
            <th width="60">操作</th>
        </tr>
    </thead>
    <tbody>
    <?php if (count($imageList) > 0) { ?>
	<?php foreach ($imageList as $productImageId => $imageInfo) { ?>
    <tr>
        <td align="center"><?php echo CHtml::tag('img',
				array('src' => $imageInfo['thumbnail_image_url']))
				. CHtml::hiddenField(
						'imageList[' . $productImageId
								. '][thumbnail_image_url]',
						$imageInfo['thumbnail_image_url'])
				. CHtml::hiddenField(
						'imageList[' . $productImageId . '][product_id]',
						$imageInfo['product_id']);
			?></td>
        <td><?php echo CHtml::textField(
				'imageList[' . $productImageId . '][file_name]',
				$imageInfo['file_name'], array('size' => 120));
						   ?></td>
        <td align="center"><?php echo CHtml::textField(
				'imageList[' . $productImageId . '][sort_order]',
				$imageInfo['sort_order'], array('size' => 2));
						   ?></td>
        <td align="center"><?php echo CHtml::checkBox(
				'imageList[' . $productImageId . '][is_released]',
				$imageInfo['is_released']);
						   ?></td>
        <td align="center"><?php echo CHtml::link('删除',
				array('deleteImage', 'product_image_id' => $productImageId,
						'product_id' => $imageInfo['product_id']),
				array('class' => 'delete-image-button'));
			?></td>
    </tr>
    <?php } ?>
    <?php } ?>
    </tbody>
</table>

<div style="margin-top: 10px;">
    <p class="field-tips">
		请点击下方按钮进行图片上传，可以一次选取多张图片<br />
		上传的图片宽大于 800 像素、高大于 800 像素<br />
		点击 "Browse" 按键上传图片
	</p>
	<?php $this
		->widget('ext.uploadify.EuploadifyWidget',
				array('model' => $product, 'attribute' => 'images',
						'options' => array('multi' => true, 'auto' => true,
								'script' => $this->createUrl('uploadImage'),
								'scriptData' => array(
										'PHPSESSID' => session_id(),
										'product_id' => $product->isNewRecord ? 0
												: $product->primaryKey),
								'fileDesc' => '*.jpg;*.gif;*.png;*.jpeg',
								'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',),
						'callbacks' => array(
								'onComplete' => 'uploadifyOnComplete',),));
	?>
</div>
<script type="text/javascript">
<!--
<?php ob_start(); ?>
<tr>
    <td align="center"><?php echo CHtml::image('{thumbnail_image_url}')
		. CHtml::hiddenField(
				'imageList[{product_image_id}][thumbnail_image_url]',
				'{thumbnail_image_url}')
		. CHtml::hiddenField('imageList[{product_image_id}][product_id]',
				'{product_id}');
		?></td>
    <td>{file_name}</td>
    <td align="center"><?php echo CHtml::textField(
		'imageList[{product_image_id}][sort_order]', 0, array('size' => 2));
					   ?></td>
    <td align="center"><?php echo CHtml::checkbox(
		'imageList[{product_image_id}][is_released]', 1);
					   ?></td>
    <td align="center"><?php echo CHtml::link('删除',
		array('deleteImage', 'product_image_id' => '{product_image_id}',
				'product_id' => '{product_id}'),
		array('class' => 'delete-image-button'));
		?></td>
</tr>
<?php $uploadedTrTpl = ob_get_clean(); ?>
var uploadedTrTpl = <?php echo CJavaScript::jsonEncode($uploadedTrTpl); ?>;

function uploadifyOnComplete(event, queueID, fileObj, response, data) {
	var data = $.parseJSON(response);
	if (data.result == true) {
		var s = uploadedTrTpl;
		s = s.replace(/{product_image_id}/g, data.product_image_id);
		s = s.replace(/{product_id}/g, data.product_id);
		s = s.replace(new RegExp(encodeURI('{product_image_id}'), 'g'), data.product_image_id);
		s = s.replace(new RegExp(encodeURI('{product_id}'), 'g'), data.product_id);
		s = s.replace(/{thumbnail_image_url}/g, data.thumbnail_image_url);
		s = s.replace(/{file_name}/g, data.file_name);
		$('#image-table').append(s).trigger('vary');
	} else {
		alert(data.message);
	}
}

$(function() {
	$('#image-table').bind('vary', function() {
		if ($(this).find('tbody tr').length == 0)
			$(this).hide();
		else
		$(this).show();
	});

	$('.delete-image-button').live('click', function() {
		var self = this;
		$.ajax({
			url: $(this).attr('href'),
			success: function(data) {
			if (data == '1') {
				var index = $(self).parents('tr:first').index();
				$(self).parents('tr:first').remove();
				$('#image-table').trigger('vary');
			} else {
				alert('ERROR: ' + data);
			}
		}
	});
	return false;
});

});
//-->
</script>