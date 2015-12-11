<html>
<head>
<title>Image Upload Tips Message</title>
</head>
<body>
<script type="text/javascript">
if (<?php echo $isUploaded == true ? 'true' : 'false'; ?> == true) {
	var win = window.parent;
	win.document.getElementById('src').value = "<?php echo $imageUrl; ?>";
	if (typeof(win.ImageDialog) != "undefined") { 
		if (win.ImageDialog.getImageData) { win.ImageDialog.getImageData(); }
		if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage("<?php echo $imageUrl; ?>");
	}
} else {
	alert("<?php echo $message; ?>");
}

window.location = "<?php echo Yii::app()->createUrl('site/tinymceImageUpload',
		array(
				'editorBaseUrl' => $editorBaseUrl
		));
				   ?>";
</script>
</body>
</html>
