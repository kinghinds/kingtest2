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
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/user-group.png'); ?>管理员角色</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('createManagerRole')) { ?>
		<?php echo CHtml::link('<span>添加</span>', array('create', 
				'returnUrl' => Yii::app()->request->url), 
				array('class' => 'button')); ?>
		<?php } ?>
	</div>
</div>

<div class="content">

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
				'name' => 'manager_role_id', 
				'headerHtmlOptions' => array(
					'class' => 'id-column',
					'style' => 'width:80px;'
				),
				'htmlOptions' => array(
					'class' => 'idcolumn',
					'align' => 'center'
				)
			),
			array('name' => 'name'),
			array(
				'name' => 'create_time',
				'htmlOptions' => array(
					'align' => 'center',
					'width' => '150px'
				)
			),
			array(
				'name' => 'update_time',
				'htmlOptions' => array(
					'align' => 'center',
					'width' => '150px'
				)
			),
			array(
				'class' => 'CButtonColumn',
				'header' => ManagerRole::model()->getAttributeLabel('privileges'),
				'headerHtmlOptions' => array('style' => 'width:100px;'),
				'htmlOptions' => array('align' => 'center'),
				'template' => '{privileges}',
				'buttons' => array(
					'privileges' => array(
						'label' => '设置权限',
						'url' => 'array("updatePrivilege", "id" => $data->primaryKey, "return_url" => Yii::app()->request->url)'
					)
				),
				'visible' => Yii::app()->user->checkAccess('updateManagerRolePrivilege')
			),
			array(
				'class' => 'CButtonColumn',
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
						'visible' => 'Yii::app()->user->checkAccess("updateManagerRole")'
					),
					'delete' => array(
						'visible' => '$data->is_admin ? false : Yii::app()->user->checkAccess("deleteManagerRole")'	
					)
				)
			)
		)
	)); ?>

</div>
