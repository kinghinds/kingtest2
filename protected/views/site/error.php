<div class="wrap">
	<div class="container">
		<div class="bg_top"></div>
		<div class="errorcon">
			<h1><?php echo $code; ?>错误</h1>
			<div><?php echo CHtml::encode($message); ?></div>
			<div><a href="<?php echo Yii::app()->createUrl('site/index'); ?>">点击这里返回到主页</a></div>
		</div>
		<div class="bg_bottom"></div>
	</div>
</div>

<style>
.errorcon{
	margin:0 auto;
	padding:50px 20px;
	width:915px;
	background-color:#000;
	color:#fff;
}
</style>

