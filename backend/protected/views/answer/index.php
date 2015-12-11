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
<?php $this->endClip(); ?>

<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>咨询回复</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>返回咨询管理</span>', array('feedback/index'), array(
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
			'onsubmit' => 'batchDelete("确认要删除选中数据吗?", this, "answer_id[]"); return false;'
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
					'id' => 'answer_id',
					'headerHtmlOptions' => array('style' => 'width:5%;'),
					'htmlOptions' => array('align' => 'center')
				),
				array(
					'name' => 'answer_id',
					'headerHtmlOptions' => array('style' => 'width:5%;'),
					'htmlOptions' => array('align' => 'center')
				),
				array('name' => 'feedback_id',
					'value' => '$data->feedback->content',
					'headerHtmlOptions' => array('style' => 'width:30%;'),
					'htmlOptions' => array('align' => 'center'),
					'type' => 'html'
				),
				array('name' => 'content',
					'headerHtmlOptions' => array('style' => 'width:30%;'),
					'htmlOptions' => array('align' => 'center'),
					'type' => 'html'
				),
				array(
					'name' => 'reply_time',
					'htmlOptions' => array('align' => 'center', 'width' => '15%')
				),
				array(
					'class' => 'CButtonColumn',
					'header' => '操作',
					'headerHtmlOptions' => array('style' => 'width:5%;'),
					'htmlOptions' => array('align' => 'center'),
					'template' => '{view} {update}',
					'buttons' => array(
						'view' => array(
							'url' => 'array("view", "id" => $data->primaryKey, "return_url" => Yii::app()->request->url)',
						),
						'update' => array(
							'url' => 'array("update", "id" => $data->primaryKey)',
							'visible' => 'Yii::app()->user->checkAccess("updateAnswer")'	
						)
					)					
				)
			)
		)); ?>

		<?php if (Yii::app()->user->checkAccess('deleteAnswer')) { ?>
		<input type="submit" value="删除选中项" />	
		<?php } ?>

		<?php echo CHtml::endForm(); ?>

	</div>
</div>