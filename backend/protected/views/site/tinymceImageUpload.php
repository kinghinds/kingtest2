<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Image Upload</title>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<style>
body {
	margin: 0;
	padding: 0;
	font-size: 12px;
	line-height: 25px;
	background-color: #FFF;
}
</style>

</head>
<body>
<div style="width: 330px">
	<?php echo CHtml::form(array(
				''
		), 'post',
		array(
				'id' => 'myForm',
				'enctype' => 'multipart/form-data',
				'onsubmit' => 'return checkForm()'
		));
	?>
	<?php echo CHtml::fileField('imageFile'); ?>
	<?php echo CHtml::hiddenField('editor_base_url', $editorBaseUrl); ?>
	<input type="submit" value="&#x4E0A;&#x4F20;" /> 
	<?php echo CHtml::endForm(); ?>
</div>
<script type="text/javascript">
<!--
function checkForm() {
	var fileName = $('#imageFile').val();
	if (fileName.length <= 0) {
		alert('\u8BF7\u9009\u62E9\u9700\u8981\u4E0A\u4F20\u7684\u56FE\u7247');
		return false;
	}

	var fileExt = fileName.toLowerCase().split('.').pop();
	allowExts = new Array('jpg', 'jpeg', 'gif', 'png');

	if ($.inArray(fileExt, allowExts) == -1) {
		alert('\u4E0D\u5141\u8BB8\u4E0A\u4F20\u7684\u56FE\u7247\u683C\u5F0F');
		return false;
	}

	$(':submit').val('\u4E0A\u4F20\u4E2D...');
	return true;
}
//-->
</script>
</body>
</html>
