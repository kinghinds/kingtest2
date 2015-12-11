<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
<?php echo CHtml::tag('title', array(), $this->pageTitle) . "\r\n"; ?>
<!-- <?php echo CHtml::tag('title', array(), Yii::app()->name) . "\r\n"; ?> -->
<?php echo CHtml::metaTag('Shenzhen Yunle Technology Co., Ltd. http://www.joy-cloud.com Aug 2013', 'author') . "\r\n"; ?>
<?php echo CHtml::metaTag('noindex', 'robots') . "\r\n"; ?>
<?php echo CHtml::metaTag('no', null, 'imagetoolbar') . "\r\n"; ?>
<?php echo CHtml::cssFile(Helper::mediaUrl('stylesheet/stylesheet.css')) . "\r\n"; ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl . '/javascript/superfish/js/superfish.js') . "\r\n"; ?>
<script type="text/javascript">
$(function() {
	$('#menu > ul').superfish({
		hoverClass	 : 'sfHover',
		pathClass	 : 'overideThisToUse',
		delay		 : 0,
		animation	 : {height: 'show'},
		speed		 : 'normal',
		autoArrows   : false,
		dropShadows  : false, 
		disableHI	 : false, /* set to true to disable hoverIntent detection */
		onInit		 : function(){},
		onBeforeShow : function(){},
		onShow		 : function(){},
		onHide		 : function(){}
	});
	
	$('#menu > ul').css('display', 'block');
});
 
function getURLVar(urlVarName) {
	var urlHalves = String(document.location).split('?');
	var urlVarValue = '';
	
	if (urlHalves[1]) {
		var urlVars = urlHalves[1].split('&');

		for (var i = 0; i <= (urlVars.length); i++) {
			if (urlVars[i]) {
				var urlVarPair = urlVars[i].split('=');
				
				//if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
					urlVarValue = urlVarPair[1];
				//}
			}
		}
	}
	
	return urlVarValue;
} 

$(function() {
	route = getURLVar('route');	
	if (!route) {
		$('#dashboard').addClass('selected');
	} else {
		part = route.split('/');	
		url = part[0];		
		if (part[1]) {
			url += '/' + part[1];
		}	
		$('a[href*=\'' + url + '\']').parents('li').addClass('selected');
	}
});
</script>
<script type="text/javascript">
$(function(){
	$('.clean-cache').live('click', function(){
		$.ajax({
			type: 'POST',
			url: $(this).attr('href'),
			async : true,
			dataType: 'JSON',
			data: {
				rand: Math.random()
			},
			success: function(response) {
				if (response.result) {
					alert('缓存已成功清除');
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alert('缓存清除失败，请稍候重试！');
			}
		});
		return false;
	});
});
</script>
<?php echo $this->clips['extraHead']; ?>
</head>
<body>
<div id="container">
	<div id="header">
		<div class="div1">
			<div class="div2">
				<?php echo CHtml::image(Yii::app()->baseUrl
						. '/image/logo.png'); ?>
			</div>
			<div class="div3">
				<?php echo CHtml::image(Yii::app()->baseUrl . '/image/lock.png',
						'', array('style' => 'position: relative; top: 0px;')); ?>
				您的登录账号为
				<span><?php echo Yii::app()->user->name; ?></span>
				<ul class="toplink">
					<li><?php echo CHtml::link('查看网站', 
							Yii::app()->params['frontendBaseUrl'] . '/', 
							array('target' => '_blank', 'class' => 'top'));	?></li>
					<?php if (Yii::app()->user->checkAccess('cleanCache')) { ?>
					<li><?php echo CHtml::link('清除缓存', array('site/cleanCache'), 
							array('class' => 'top clean-cache')); ?></li>
					<?php } ?>
					<?php if (Yii::app()->user->checkAccess('updateSetting')) { ?>
					<li><?php echo CHtml::link('系统设置', 
							array('site/setting', 'return_url' => Yii::app()->request->url),
							array('class' => 'top')); ?></li>
					<?php } ?>
					<li><?php echo CHtml::link('修改密码', array('site/password', 'return_url' => Yii::app()->request->url), 
							array('class' => 'top')); ?></li>
					<li><?php echo CHtml::link('退出', array('site/logout'), 
							array('class' => 'top')); ?></li>
				</ul>
			</div>
			<?php if (Yii::app()->user->hasFlash('message')) { ?>
			<?php preg_match('/(\w+)#(.*)/', Yii::app()->user->getFlash('message'), $matches); ?>
			<div id="flash_message" class="flash-message">
				<span class="<?php echo $matches[1]; ?>"><?php echo $matches[2]; ?></span>
			</div>
			<?php } ?>
		</div>
		<div id="menu">
			<?php $this->widget('Menu'); ?>			
		</div>
	</div>
	<div id="content">
		<?php $this->widget('zii.widgets.CBreadcrumbs',	array(
			'links' => $this->breadcrumbs,
			'htmlOptions' => array('class' => 'breadcrumb')
		));?>
		<div class="box">
			<?php echo $content; ?>
		</div>
	</div>
</div>
<div id="footer">
	Copyright © 2015 Shenzhen <a href="http://www.kinghinds.com" target="_blank">King Hinds</a> Science and Technology Co., Ltd.
</div>
</body>
</html>