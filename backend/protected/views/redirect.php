<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo CHtml::metaTag('text/html; charset=utf-8', null, 'Content-Type')
		. "\n";
?>
<?php echo CHtml::metaTag($delay . '; url=' . CHtml::normalizeUrl($url), null,
		'refresh');
?>
<title><?php echo $this->pageTitle; ?></title>
<?php echo CHtml::cssFile(Helper::mediaUrl('resources/screen.css'),
		'screen, projection');
?>
<?php echo CHtml::cssFile(Helper::mediaUrl('resources/print.css'), 'print'); ?>
<!--[if lt IE 8]>
<?php echo CHtml::cssFile(Helper::mediaUrl('resources/ie.css'),
		'screen, projection');
?>
<![endif]-->
<style type="text/css">
#redirectMessage {
	margin: 18px 0;
	text-align: center;
	font-size: 1.4em;
	font-family: "微软雅黑";
}
</style>
<script type="text/javascript">
//<![CDATA[
if ( navigator.product == 'Gecko' )
    setTimeout("moz_redirect()",<?php echo $delay * 1000 - 500; ?>);
function moz_redirect() {
    window.location.replace('<?php echo CHtml::normalizeUrl($url); ?>');
}
//>
</script>
</head>
<body dir="<?php echo Yii::app()->locale->orientation; ?>">
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<div id="redirectMessage"><?php echo $message; ?></div>
	<p align="center">
		请稍等, 系统将在 <?php echo $delay; ?> 秒后切换页面... <br />
		<?php echo CHtml::link('立即切换', $url); ?>
	</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
</body>
</html>
