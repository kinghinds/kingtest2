<?php $this->beginClip('extraHead'); ?>
<script type="text/javascript">
<!--
$(function() {
	$('.submit-button').bind('click', function() {
		$('#form1').trigger('submit');
		return false;
	});
});
//-->
</script>
<?php $this->endClip(); ?>


<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/user-group.png'); ?><?php echo $managerRole->isNewRecord ? '添加' : '修改'; ?>管理员角色</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array('class' => 'button submit-button')); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array('class' => 'button')); ?>
	</div>
</div>

<div class="content">
	<?php echo CHtml::errorSummary($managerRole); ?>
	<?php echo CHtml::form(
		($managerRole->isNewRecord ? array('') : array('', 'id' => $managerRole->primaryKey, 'return_url' => $returnUrl)), 
		'post', 
		array('id' => 'form1')
	); ?>

	<table class="form">
		<tr>
			<td><?php echo CHtml::activeLabelEx($managerRole, 'name'); ?></td>
			<td>
				<?php echo CHtml::activeTextField($managerRole, 'name', array(
						'size' => 40)); ?>
			</td>
		</tr>
		<tr>
			<td><?php echo CHtml::activeLabelEx($managerRole, 'description'); ?></td>
			<td>
				<?php echo CHtml::activeTextArea($managerRole, 'description', 
						array('cols' => 60, 'rows' => 5)); ?>
			</td>
		</tr>
	</table>
	<?php echo CHtml::endForm(); ?>
</div>
