<?php echo CHtml::form(Yii::app()->request->url, 'post', array(
		'class' => 'sort-specify-form'
)); ?>
<div>
	移动至
	<strong>编号</strong> 
	<?php echo CHtml::textField('target_id', '', array('size' => '6'));	?>
	<?php echo CHtml::radioButtonList('pos', '2', 
			array('1' => '之前', '2' => '之后'), 
			array('separator' => '&nbsp;')
	); ?>			
	<?php echo CHtml::submitButton('修改排序'); ?>			
</div>
<?php echo CHtml::endForm(); ?>

<script type="text/javascript">
$(function(){
	$('.sort-specify-form').submit(function() {
		self = this;
		targetId = $('#target_id').val();
		if (targetId.length == 0) {
			alert('请输入编号');
			$('#target_id').focus();
			return false;
		} else if (isInt(targetId) == false) {
			alert('编号格式不正确，排序号必须为正整数');
			$('#target_id').focus();
			return false;
		}

		$.ajax({
			type : 'POST',
			url : $(self).attr('action'),
			dataType : 'json',
			async : false,
			data : {
				rand : Math.random(),
				target_id : targetId,
				pos : $('input[name=pos]:checked').val()
			},
			beforeSend : function() {
				$(self).html('<img src="image/loading.gif" alt="loading" />');
			},
			complete : function() {
				Sort.colseSpecify();
			},
			success : function(response) {
				if (response.result) {
					Sort.reloadGridView();
				} else {
					alert(response.message);
				} 
			}
		});
		
		return false;
	});
});
</script>
