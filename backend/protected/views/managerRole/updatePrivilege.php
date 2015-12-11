<?php $this->beginClip('extraHead'); ?>
<?php echo CHtml::scriptFile(Helper::mediaUrl('javascript/jquery.masonry.min.js')) . "\r\n"; ?>
<script type="text/javascript">
$(function() {
	$('.privilege_main').imagesLoaded(function() {
		$(this).masonry({
			itemSelector : '.privilege_wide'
		});
	});
});
</script>
<script type="text/javascript">
<!--
$(function() {
	$('.submit-form').bind('click', function() {
		$('#form1').submit();
		return false;
	});

	$('.top-selected-all').click(function(){
		$('.privileges').attr('checked', 'checked');
		return false;
	});
	
	$('.top-inverse').click(function(){
		$.each($('.privileges'), function(i, n){
			if ($(n).attr('checked') == true || $(n).attr('checked') == 'checked') {
				$(n).removeAttr('checked');
			} else {
				$(n).attr('checked', 'checked');
			}
		});
		return false;
	});
	
	$('.selected-all').click(function(){
		$(this).parent().next().find('.privileges').attr('checked', 'checked');
		return false;
	});
	
	$('.inverse').click(function(){
		$.each($(this).parent().next().find('.privileges'), function(i, n){
			if ($(n).attr('checked') == true 
					|| $(n).attr('checked') == 'checked') {
				$(n).removeAttr('checked');
			} else {
				$(n).attr('checked', 'checked');
			}
		});
		return false;
	});
	
	$(':hidden[name^=UpdateManagerRolePrivilegeForm]').remove();
	$.each($('.privilege_main :checkbox'), function(i, n) {
		$(n).removeAttr('id');
		$(n).next().click(function(){
			if ($(n).attr('checked') == true 
					|| $(n).attr('checked') == 'checked') {
				$(n).removeAttr('checked');
			} else {
				$(n).attr('checked', 'checked');
			}
		});
	});
	$(':hidden[name^=UpdateManagerRolePrivilegeForm]').remove();
});
//-->
</script>
<?php $this->endClip(); ?>

<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/user-group.png'); ?>修改管理员角色权限</h1>
	<div class="buttons">		
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button submit-form')); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>

<div class="content">

	<?php echo CHtml::errorSummary($managerRole); ?>

	<?php echo CHtml::form(
		array('', 'id' => $managerRole->primaryKey, 'return_url' => $returnUrl), 
		'post', 
		array('id' => 'form1')
	); ?>

	<table class="form">
		<tr>
			<td><?php echo CHtml::activeLabelEx($managerRole, 'name'); ?></td>
			<td><?php echo $managerRole->name; ?></td>
		</tr>
		<tr>
			<td valign="top"><?php echo CHtml::activeLabelEx($form,	'privileges'); ?></td>
			<td>
				<div style="max-width:900px">
					<div style="margin-bottom:5px;">
						<a href="#" class="top-selected-all">全选</a> /
						<a href="#" class="top-inverse">反选</a>
					</div>
					<div class="privilege_main">
						<?php foreach ($privileges as $i => $privilege) { ?>
						<div class="privilege_wide">
							<h3 class="title"><?php echo $privilege['label']; ?></h3>
							<div class="selectbox" style="margin-bottom:5px;">
								<a href="#" class="selected-all">全选</a> /
								<a href="#" class="inverse">反选</a>
							</div>
							<div class="optionbox">
								<?php echo CHtml::activeCheckBoxList($form,	
										'privileges', 
										CHtml::listData($privilege['items'], 
											'privilege', 'label'
										), 
										array(
											'id' => false, 
											'class' => 'privileges'
										)
								); ?>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php echo Helper::fieldTips('权限修改后管理员需要重新登录系统方可生效'); ?>
			</td>
		</tr>
	</table>

	<?php echo CHtml::endForm(); ?>

</div>
