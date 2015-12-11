
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="Shortcut Icon" href="<?php echo Helper::mediaUrl('favicon.ico?v1'); ?>" type="image/x-icon" />
<meta name="Author" content="Shenzhen Doocom Technology Co., Ltd. http://www.doocom.cn, Dec 2012" />
<?php echo CHtml::tag('title', array(), $this->pageTitle) . "\r\n"; ?>
<?php if ($this->metaKeywords) echo CHtml::metaTag($this->metaKeywords, 'keywords') . "\r\n"; ?>
<?php if ($this->metaDescription) echo CHtml::metaTag($this->metaDescription, 'description') . "\r\n"; ?>
<?php echo CHtml::cssFile(Helper::mediaUrl('inc/style.css')) . "\r\n"; ?>
<?php echo CHtml::scriptFile(Helper::mediaUrl('inc/jquery.js')) . "\r\n"; ?>
<?php echo CHtml::scriptFile(Helper::mediaUrl('inc/func.js')) . "\r\n"; ?>
<?php echo CHtml::scriptFile(Helper::mediaUrl('inc/jquery.cycle.all.min.js')) . "\r\n"; ?>
<?php echo CHtml::scriptFile(Helper::mediaUrl('inc/jquery.masonry.min.js')) . "\r\n"; ?>

</head>

<body>
<div class="wrapper">
	<?php $this->widget('Header'); ?>
    <?php echo $content; ?>
    <?php $this->widget('Footer'); ?>
</div>

<script type="text/javascript">
$(function(){
	var $container = $('.sitelist');

	$container.imagesLoaded( function(){
      $container.masonry({
        itemSelector : '.sitelist li'
      });
    });
	
}); 
</script>

</body>
</html>
