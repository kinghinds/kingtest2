<?php $this->beginClip('extraHead'); ?>
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
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/log.png'); ?>操作日志</h1>
</div>

<div class="content">
	<div class="filter-form">
		<?php echo CHtml::form(array(''), 'get');
		?> 
		<?php if (count($actionTypeOptions) > 0) { ?>
		操作类型 
		<?php echo CHtml::dropDownList('actionType', $actionType,
			$actionTypeOptions, array('empty' => ''));
		?>
		<?php } ?> 
		<?php if (count($filterOptions) > 0) { ?> 
		过滤 
		<?php echo CHtml::dropDownList('filter', $filter, $filterOptions,
			array('empty' => ''));
		?>
		<?php } ?> 
		关键词 
		<?php echo CHtml::textField('keyword', $keyword); ?> 
		<?php echo CHtml::submitButton('搜索'); ?>
		<?php echo CHtml::resetButton('重置',
		array(
				'onclick' => "window.location.href='"
						. $this->createUrl('index') . "'"));
		?> 
		<?php echo CHtml::endForm(); ?>
	</div>
	
	<div class="clearfix">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
						'pager' => array('cssFile' => false),
						'cssFile' => false,
						'htmlOptions' => array('class' => 'list-view'),
						'itemsCssClass' => 'list items tree-table',
						'loadingCssClass' => 'list-view-loading',
						'dataProvider' => $dataProvider, 'selectableRows' => 0,
						'columns' => array(
								array('name' => 'manager_log_id',
										'headerHtmlOptions' => array(
												'class' => 'id-column',
												'style' => 'width:80px;'),
										'htmlOptions' => array(
												'class' => 'id-column',
												'align' => 'center')),
								array('name' => 'result',
										'value' => '$data->result ? "成功" : "<font color=\"red\">失败</font>"',
										'htmlOptions' => array(
												'align' => 'center'),
										'type' => 'raw'),
								array('name' => 'action_type_id',
										'value' => '$data->action_type'),
								array('name' => 'summary',
										'value' => 'nl2br($data->summary)',
										'type' => 'raw'),
								array('name' => 'create_time',
										'value' => '$data->create_time . " <font color=\"gray\">(" . Helper::time_since(strtotime($data->create_time)) . ")</font>"',
										'type' => 'raw'),
								array('name' => 'operator_user_name'),
								array('name' => 'ip'))));
		?>
	</div>
</div>