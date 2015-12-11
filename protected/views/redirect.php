<?php $this->beginClip('extraHead'); ?>
<meta http-equiv="refresh" content="<?php echo $delay; ?>; url=<?php echo $url; ?>" />
<style type="text/css">
#pbody { background-image: url(<?php echo Helper::mediaUrl('images/banner_other.jpg'); ?>); }
</style>
<?php $this->endClip(); ?>
<div class="content">
	<div class="container">
		<div style="padding:20px;">
			<div class="newstext">
				<div><?php echo CHtml::encode($message); ?></div>
				<div><?php echo Yii::t('common',
		'Page is redirect, please wait ...');
					 ?></div>
				<div><?php echo CHtml::link(
		Yii::t('common',
				'If your browser does not support frames, please click here'),
		$url);
					 ?></div>
			</div>
		</div>
	</div>
</div>
