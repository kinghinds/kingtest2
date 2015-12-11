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
	<h1>
		<?php echo CHtml::image(Yii::app()->baseUrl . '/image/user.png'); ?>
		管理员
	</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('createManager')) { ?>
		<?php echo CHtml::link('<span>添加</span>', array('create'),
				array('class' => 'button')); ?>
		<?php } ?>
	</div>
</div>

<div class="content">
	<div class="filter-form">
		<?php echo CHtml::form(array(''), 'get'); ?> 
		<?php if (count($filterOptions) > 0) { ?>
			条件筛选
			<?php echo CHtml::dropDownList('filter', $filter, $filterOptions,
					array('empty' => '')); ?>
		<?php } ?>
		关键词 
		<?php echo CHtml::textField('keyword', $keyword); ?> 
		<?php echo CHtml::submitButton('搜索'); ?>
		<?php echo CHtml::resetButton('重置', array(
				'onclick' => "window.location.href='"
					 	. $this->createUrl('index') . "'")); ?> 
		<?php echo Helper::fieldTips('如果你已知道数据编号，可用 #编号 来搜索数据，如：#12'); ?> 
		<?php echo CHtml::endForm(); ?>
	</div>
	
	<div class="clearfix">

		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'pager' => array('cssFile' => false),
			'cssFile' => false,
			'htmlOptions' => array('class' => 'list-view'),
			'itemsCssClass' => 'list items tree-table',
			'loadingCssClass' => 'list-view-loading',
			'dataProvider' => $dataProvider, 
			'selectableRows' => 0,
			'columns' => array(
				array(
					'name' => 'manager_id',
					'headerHtmlOptions' => array(
						'class' => 'id-column', 
						'style' => 'width:80px;'
					),
					'htmlOptions' => array('align' => 'center')
				),
				array('name' => 'login_name'),
				array(
					'name' => 'manager_role_id', 
					'value' => '$data->manager_role_id > 0 && $data->managerRole ? $data->managerRole->name : null'
				),
				array(
					'name' => 'last_login_time',
					'value' => 'strtotime($data->last_login_time) > 0 ? Helper::time_since(strtotime($data->last_login_time)) : null'
				),
				array(
					'name' => 'last_login_ip'
				),
				array(
					'name' => 'login_times',
					'htmlOptions' => array('align' => 'right')
				),
				array(
					'name' => 'is_allow_login',
					'value' => '$data->is_allow_login ? "是" : "<font color=\"red\">否</font>"',
					'type' => 'raw',
					'htmlOptions' => array('align' => 'center')
				),
				array('class' => 'CButtonColumn',
					'header' => '操作',
					'headerHtmlOptions' => array('style' => 'width:100px;'),
					'htmlOptions' => array('align' => 'center'),
					'template' => '{view} {update} {delete}',
					'buttons' => array(
						'view' => array(
							'url' => 'array("view", "id" => $data->primaryKey, "return_url" => Yii::app()->request->url)',
						),
						'update' => array(
							'url' => 'array("update", "id" => $data->primaryKey, "return_url" => Yii::app()->request->url)',
							'visible' => 'Yii::app()->user->checkAccess("updateManager")'
						),
						'delete' => array(
							'visible' => '$data->is_admin ? false : Yii::app()->user->checkAccess("deleteManager")'
						)
					)					
				)
			)
		)); ?>

	</div>
</div>