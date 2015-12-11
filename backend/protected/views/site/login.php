<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
<?php //echo CHtml::tag('title', array(), $this->pageTitle) . "\r\n"; ?>
<?php echo CHtml::tag('title', array(), Yii::app()->name) . "\r\n"; ?>
<?php echo CHtml::cssFile(Helper::mediaUrl('stylesheet/stylesheet.css')) . "\r\n"; ?>
<script type="text/javascript">
$(function(){
	$('#form1').find(':text,:password').bind('keypress', function(e) {
		if (e.keyCode === 13) {
			$('#form1').trigger('submit');
		}
	});
});
</script>
<style>
#yw0{ vertical-align:middle;}
</style>
</head>
<body>
<div id="container">
	<div id="header">
		<div class="div1">
			<div class="div2"><?php echo CHtml::image(Yii::app()->baseUrl . '/image/logo.png'); ?></div>
		</div>
	</div>
	<div id="content">
		<div class="box" style="width: 400px; min-height: 300px; margin-top: 40px; margin-left: auto; margin-right: auto;">
			<div class="heading">
				<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/lockscreen.png'); ?>请输入您的登录信息</h1>
			</div>
			<div class="content" style="min-height: 150px; overflow: hidden;">
				
				<?php $form = $this->beginWidget('CActiveForm', array(
						'id' => 'form1', 
						'enableAjaxValidation' => true,
						'enableClientValidation' => true,
						'clientOptions' => array('validateOnSubmit' => true),
						'focus' => array($model, 'loginName')
				)); ?>	

				<?php echo $form->errorSummary($model, '', null, array('class' => 'warning')); ?>

					<table style="width: 100%;">
						<tr>
							<td style="text-align: center; width: 130px;" rowspan="4" valign="top"><?php echo CHtml::image(Yii::app()->baseUrl . '/image/login.png', ''); ?></td>
						</tr>
						<tr>
							<td>
								<?php echo $form->label($model, 'loginName', array('for' => 'form_login')); ?>
								<br />
								<?php echo $form->textField($model, 'loginName', array('class' => 'input', 'size' => 20)); ?>
								<br />
								<br />
								<?php echo $form->label($model, 'loginPassword', array('for' => 'form_password')); ?>
								<br />
								<?php echo $form->passwordField($model, 'loginPassword', array('class' => 'input', 'size' => 20)); ?>
								<?php if (!YII_DEBUG && extension_loaded('gd')) { ?>
								<br />
								<br />
								<?php echo $form->label($model, 'validationCode', array('for' => 'validationCode')); ?>
								<br />
								<?php echo $form->textField($model, 'validationCode', array('class' => 'input', 'size' => 6)); ?>
								<?php $this->widget('CCaptcha'); ?>
								<br />
								<?php } ?>			
								<?php if (Yii::app()->user->isGuest == false) { ?>
								<p><?php echo CHtml::link('You has logged by ' . Yii::app()->user->name . ' before', Yii::app()->user->returnUrl); ?></p>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td style="text-align: right;"><?php echo CHtml::linkButton('<span>登 录</span>', array('class' => 'button')); ?></td>
						</tr>
					</table>

				<?php $this->endWidget(); ?>
				
			</div>
		</div>
	</div>	
</div>
<div id="footer">©2015 <a href="http://www.kinghinds.com" target="_blank">天下楚云</a></div>
</body>
</html>