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
var UPDATE_IS_RELEASED_URL = '<?php echo $this->createUrl("updateIsReleased"); ?>';
$(function() {
    $('.tree-table a.is-released-0').live('click', function() {
        $(this).attr('class', 'is-released-1');
        var m = $(this).attr('href').match(/#(\d+)(\w+)/);
        updateIsReleased(UPDATE_IS_RELEASED_URL, m[1], m[2], 1);
        return false;
    });
    
    $('.tree-table a.is-released-1').live('click', function() {
        $(this).attr('class', 'is-released-0');
        var m = $(this).attr('href').match(/#(\d+)(\w+)/);
        updateIsReleased(UPDATE_IS_RELEASED_URL, m[1], m[2], 0);
        return false;
    });
});
//-->
</script>
<?php $this->endClip(); ?>

<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>页面</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('createPage')) { ?>		
		<?php echo CHtml::link('<span>添加</span>', array('create'), array(
				'class' => 'button')); ?>
		<?php } ?>				
	</div>
</div>

<div class="content">
	<div class="filter-form">
		<?php echo CHtml::form(array(''), 'get');
		?> 
		关键词 
		<?php echo CHtml::textField('keyword', $keyword); ?> 
		<?php echo CHtml::submitButton('搜索'); ?>
		<?php echo CHtml::resetButton('重置',
		array(
				'onclick' => "window.location.href='"
						. $this->createUrl('page') . "'"));
		?> 
		<?php echo Helper::fieldTips('如果你已知道数据编号，可用 #编号 来搜索数据，如：#12'); ?>
		<?php echo CHtml::endForm(); ?>
	</div>
	
	<div class="clearfix">

		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'pager' => array('cssFile' => false),
			'cssFile' => false,
			'htmlOptions' => array('class' => 'list-view'),
			'itemsCssClass' => 'list tree-table',
			'loadingCssClass' => 'list-view-loading',
			'dataProvider' => $dataProvider, 'selectableRows' => 0,
			'columns' => array(
				array(
					'name' => 'page_id',
					'headerHtmlOptions' => array('style' => 'width:80px;'),
					'htmlOptions' => array('align' => 'center')
				),
				array(
					'name' => 'title',
					'value' => '$data->getI18nColumn("title", true)',
					'type' => 'raw'
				),
				array(
					'name' => 'parent_id',
					'value' => '($data->parent_id > 0 && $data->parent)? $data->parent->getI18nColumn("title") : null',
					'type' => 'raw'
				),
				array(
					'class' => 'CButtonColumn',
					'header' => '操作',
					'headerHtmlOptions' => array('style' => 'width:100px;'),
					'htmlOptions' => array('align' => 'center'),
					'template' => '{view} {update}',
					'buttons' => array(
						'view' => array(
							'url' => 'array("view", "id" => $data->primaryKey, "return_url" => Yii::app()->request->url)',
							'visible' => 'Yii::app()->user->checkAccess("updatePage")'
						),
						'update' => array(
							'url' => 'array("update", "id" => $data->primaryKey, "return_url" => Yii::app()->request->url)',
							'visible' => 'Yii::app()->user->checkAccess("deletePage")'							
						)
					)
				)
			)
		)); ?>

	</div>
</div>