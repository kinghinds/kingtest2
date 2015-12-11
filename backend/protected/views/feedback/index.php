<?php $this->beginClip('extraHead'); ?>
<?php Yii::app()->clientScript->registerCssFile(
		Yii::app()->clientScript->getCoreScriptUrl()
		. '/jui/css/base/jquery-ui.css'); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl . '/javascript/common.js') . "\r\n"; ?>
<script type="text/javascript">
<!--
$(function() { 
	$('table.list tbody tr').live('mouseover', function() {
		$(this).css('backgroundColor', '#EFFBFB');
	}).live('mouseout', function() {
		$(this).css('backgroundColor', 'white');
	});
});
//-->
</script>							

<script type="text/javascript">
<!--
var UPDATE_SORT_ORDER_URL = '<?php echo $this->createUrl("updateSortOrder"); ?>';
var keyUpStartTime, keyUpEndTime;
$(function() {
	$('.sort-field')
		.live('change', function() {
			var self = this;
			if (isInt($(self).val()) && $(self).val() > 0) {
				var re = /^sort_order_(\d+)$/g;
				var matchs = re.exec($(self).attr('id'));
				if (matchs.length > 1) {
					updateSortOrder(UPDATE_SORT_ORDER_URL, 
						parseInt(matchs[1]), 
						parseInt($(self).val()), self);
					$.fn.yiiGridView.update('yw0');
				}
			} else {
				alert('排序号码必须为正整数');
			}
		}).live('keyup', function() {
			var self = this;
			keyUpStartTime = new Date();
			setTimeout(function(){
				keyUpEndTime = new Date();
				if (keyUpEndTime - keyUpStartTime >= 500) {
					$(self).trigger('change');
					keyUpStartTime = null;
					keyUpEndTime = null;
				} 
			}, 500);
	});
});
//-->
</script>
<?php $this->endClip(); ?>

<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>问题咨询</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>查看所有回复</span>', array('answer/index'), array(
				'class' => 'button')); ?>
	</div>
</div>

<div class="content">

	<div class="filter-form">
		<?php echo CHtml::form(array(''), 'get');
		?>
		关键词 <?php echo CHtml::textField('keyword', $keyword); ?> 
		<?php echo CHtml::submitButton('搜索'); ?>
		<?php echo CHtml::resetButton('重置',
		array(
				'onclick' => "window.location.href='"
						. $this->createUrl('index') . "'"));
		?> 
		<?php echo Helper::fieldTips('如果你已知道数据编号，可用 #编号 来搜索数据，如：#12'); ?>
		<?php echo CHtml::endForm(); ?>
	</div>
	
	<div class="clearfix">

		<?php echo CHtml::form(array('batchDelete'), 'post', array(
			'onsubmit' => 'batchDelete("确认要删除选中数据吗?", this, "id[]"); return false;'
		)); ?>

		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'pager' => array('cssFile' => false),			
			'cssFile' => false,
			'htmlOptions' => array('class' => 'list-view'),
			'itemsCssClass' => 'list tree-table',
			'loadingCssClass' => 'list-view-loading',
			'dataProvider' => $dataProvider, 
			'selectableRows' => 2,
			'columns' => array(
				array(
					'class' => 'CCheckBoxColumn',
					'id' => 'id',
					'headerHtmlOptions' => array('style' => 'width:20px;'),
					'htmlOptions' => array('align' => 'center')
				),
				array(
					'name' => 'id',
					'headerHtmlOptions' => array('style' => 'width:80px;'),
					'htmlOptions' => array('align' => 'center')
				),
				
				array('name' => 'content'),
				
				array(
					'name' => 'create_time',
					'htmlOptions' => array('align' => 'center', 'width' => '150px')
				),
				array('name' => 'is_reply',
					'value' => '$data->statusText',
					'headerHtmlOptions' => array('style' => 'width:10%;'),
					'htmlOptions' => array('align' => 'center'),
					'type' => 'html'
				),
				
				array(
					'class' => 'CButtonColumn',
					'header' => '操作',
					'headerHtmlOptions' => array('style' => 'width:100px;'),
					'htmlOptions' => array('align' => 'center'),
					'template' => '{view} {delete}',
					'buttons' => array(
						'view' => array(
							'url' => 'array("view", "id" => $data->primaryKey)',
						),
						'delete' => array(
							'visible' => 'Yii::app()->user->checkAccess("deleteFeedback")'	
						)
					)					
				)
			)
		)); ?>

		<?php if (Yii::app()->user->checkAccess('deleteFeedback')) { ?>
		<input type="submit" value="删除选中项" />	
		<?php } ?>

		<?php echo CHtml::endForm(); ?>

	</div>
</div>