<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
<?php echo CHtml::tag('title', array(), $this->pageTitle) . "\r\n"; ?>
<?php echo CHtml::metaTag('Shenzhen Yunle Technology Co., Ltd. http://www.joy-cloud.com Aug 2013', 'author') . "\r\n"; ?>
<?php echo CHtml::metaTag('noindex', 'robots') . "\r\n"; ?>
<?php echo CHtml::metaTag('no', null, 'imagetoolbar') . "\r\n"; ?>
<?php echo $this->clips['extraHead']; ?>
</head>
<body>
<?php echo $content; ?>
</body>
</html>