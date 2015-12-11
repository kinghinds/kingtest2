<?php $this->beginClip('extraHead'); ?>
<script type="text/javascript">
<!--
$(function() {
	$('.submit-button').click(function(){
		$('#form1').trigger('submit');
		return false;
	});
});
//-->
</script>
<?php $this->endClip(); ?>

<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/user.png'); ?><?php echo $manager
		->isNewRecord ? '添加' : '修改';
																			   ?>管理员</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button submit-button')); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>

<div class="content">
	<?php if ($manager->hasErrors())
		echo CHtml::errorSummary($manager);
	?>
	<?php echo CHtml::form(
			($manager->isNewRecord ? array('') : array('', 'id' => $manager->primaryKey, 'return_url' => $returnUrl)),
			'post',
			array('id' => 'form1'));
	?>
	
	<table class="form" width="100%">
		<tr>
			<th width="160"><?php echo CHtml::activeLabelEx($manager, 'login_name'); ?>
			</th>
			<td>
				<?php if ($manager->isNewRecord) { ?> 
				<?php echo CHtml::activeTextField($manager, 'login_name'); ?>
				<?php echo Helper::fieldTips(
				'必须以英文字母开头，允许使用的字符包括 "a-z" 小写字母, "0-9" 数字, "-" 中横线');
				?>
				<?php } else { ?> 
				<?php echo $manager->login_name; ?> 
				<?php } ?>
			</td>
		</tr>
	
		<tr>
			<th><?php echo CHtml::activeLabelEx($manager, 'login_password'); ?></th>
			<td>
				<?php echo CHtml::activePasswordField($manager, 'login_password'); ?>
				<?php if ($manager->isNewRecord == false) { ?>
				<?php echo Helper::fieldTips('留空则不更改用户密码'); ?>
				<?php } ?>
			</td>
		</tr>
	
		<tr>
			<th><?php echo CHtml::activeLabelEx($manager, 'manager_role_id'); ?></th>
			<td>
				<?php if ($manager->isNewRecord == false && $manager->is_admin == true) { ?>
				<?php echo CHtml::activeDropDownList($manager, 'manager_role_id', 
						$managerRoleOptions, array('disabled' => 'disabled')); ?>
				<?php } else { ?>
				<?php echo CHtml::activeDropDownList($manager, 'manager_role_id', 
						$managerRoleOptions); ?>
				<?php } ?>
			</td>
		</tr>
	
		<tr>
			<th><?php echo CHtml::activeLabelEx($manager, 'is_allow_login'); ?></th>
			<td><?php echo CHtml::activeCheckBox($manager, 'is_allow_login'); ?></td>
		</tr>
	</table>
	<?php echo CHtml::endForm(); ?>
</div>