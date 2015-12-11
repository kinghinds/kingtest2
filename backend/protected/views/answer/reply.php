<?php $this->beginClip('extraHead'); ?>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl . '/javascript/common.js') . "\r\n"; ?>
<script type="text/javascript">
<!--
$(function() {	
	$('.toggle-tinymce-editor').click(function(){
		var tinymceId = $(this).parent().parent().find('.tinymce-editor').attr('id');
		if (tinymceId) {
			$(this).toggleClass('is-removed');
			var action = $(this).hasClass('is-removed') ? 'mceRemoveControl' : 'mceAddControl';
			tinymce.EditorManager.execCommand(action, true, tinymceId);	
		}
		return false;
	});
});
//-->
</script>
<?php $this->endClip(); ?>
<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>咨询回复</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button',
				'onclick' => "$('#form1').submit();return false;")); ?>
		<?php echo CHtml::link('<span>取消</span>', array('feedback/index'), array(
				'class' => 'button')); ?>
	</div>
</div>
<div class="content">	
	<?php echo CHtml::form($feedback->is_reply ? array('','id'=> $answer->primaryKey)
				: 
		array('', 'feedbackid' => $feedback->primaryKey),'post',
		array('id' => 'form1', 'enctype' => 'multipart/form-data'));
	?>
	<?php ob_start(); ?>
	<table class="form" width="100%">
		<tr>
			<th>咨询问题标题</th>
			<td><?php echo $feedback->content; ?></td>
		<tr>
			<th><?php echo CHtml::activeLabelEx($answer, 'content'); ?></th>
			<td>
				<?php $this
			->widget('ext.tinymce.TinyMCEWidget',
					array('model' => $answer, 'attribute' => 'content', 'htmlOptions' => array('cols' => 30, 'rows' => 10)));
				?>
				<?php echo CHtml::tag('div', array(), CHtml::link('移除/加载编辑器', '#', array('class' => 'toggle-tinymce-editor'))); ?>
			</td>
		</tr>
	</table>
	<?php
$basicContent = ob_get_clean();
$tabs = array();
$this
		->widget('zii.widgets.jui.CJuiTabs',
				array('tabs' => array('问题回复' => $basicContent)));
	?>
	<?php echo CHtml::endForm(); ?>
</div>