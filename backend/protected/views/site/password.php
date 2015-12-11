<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/user.png'); ?>修改密码</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button', 
				'onclick' => "$('#form1').submit();return false;")); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>

<div class="content">
	<?php if ($model->hasErrors())
	echo CHtml::errorSummary($model);
	?>
	
	<?php echo CHtml::form(array('', 'return_url' => $returnUrl), 'post',
		array('id' => 'form1', 'enctype' => 'multipart/form-data'));
	?>
	
	<table class="form" width="100%">
		<tr>
			<th width="160">用户名</th>
			<td><?php echo Yii::app()->user->name; ?></td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($model, 'old_password'); ?></th>
			<td>
				<?php echo CHtml::activePasswordField($model, 'old_password',
		array('autocomplete' => 'off'));
				?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($model, 'new_password'); ?></th>
			<td>
				<?php echo CHtml::activePasswordField($model, 'new_password',
		array('autocomplete' => 'off'));
				?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($model, 'new_password_again'); ?></th>
			<td>
				<?php echo CHtml::activePasswordField($model,
		'new_password_again', array('autocomplete' => 'off'));
				?>
			</td>
		</tr>
	</table>
	<?php echo CHtml::endForm(); ?>
</div>