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
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/category.png'); ?>目录</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('createPage')) { ?>
		<?php echo CHtml::link('<span>添加</span>', array('create'), array(
				'class' => 'button')); ?>
		<?php } ?>
	</div>
</div>

<div class="content">
	<div>
		<input type="button" value="折叠全部" onclick="$('.treeTable').collapseAll()" />
		<input type="button" value="展开全部" onclick="$('.treeTable').expandAll()" />
	</div>
	
	<div class="clearfix">
		<?php $this->widget('ext.treegridview.TreeGridView', array(
			'cssFile' => false,
			'htmlOptions' => array('class' => 'list-view'),
			'itemsCssClass' => 'list items tree-table',
			'loadingCssClass' => 'list-view-loading',
			'enablePagination' => false,
			'dataProvider' => $dataProvider,
			//'selectableRows' => 0,
			'idFieldName' => 'page_id',
			'titleFieldName' => 'title',
			'columns' => array(
				array(
					'name' => 'page_id',
					'header' => Page::model()->getAttributeLabel('page_id'),
					'headerHtmlOptions' => array('style' => 'width:80px;'),
					'htmlOptions' => array('align' => 'center')
				),
				array(
					'name' => 'title',
					'header' => Page::model()->getAttributeLabel('title'),
					'value' => '$data->getI18nColumn("title", true)',
					'type' => 'raw'
				),
				array(
					'name' => 'module_name',
					'header' => Page::model()->getAttributeLabel('module_name'),
					'value' => '$data->getDisplayModuleName($data->module_name, $data->module_template)',
					'type' => 'raw'
				),
				array(
					'class' => 'CButtonColumn',
					'header' => Page::model()->getAttributeLabel('sort_order'),
					'headerHtmlOptions' => array('style' => 'width:100px;'),
					'htmlOptions' => array('align' => 'center'),
					'template' => '{sort_first} {sort_previous} {sort_next} {sort_last} {sort_specify}',
					'buttons' => array(
						'sort_first' => array(
							'label' => '置顶',
							'url' => 'array("sortFirst", "id" => $data->primaryKey)',
							'click' => 'function(){ Sort.toFirst(this, function(){$(".grid-view").find(".items").treeTable({"initialState" : "expanded"})}); return false; }',
							'imageUrl' => 'image/sort_first.png'),
						'sort_previous' => array(
							'label' => '向上',
							'url' => 'array("sortPrevious", "id" => $data->primaryKey)',
							'click' => 'function(){ Sort.toPrevious(this); return false; }',
							'imageUrl' => 'image/sort_previous.png'),
						'sort_next' => array(
							'label' => '向下',
							'url' => 'array("sortNext", "id" => $data->primaryKey)',
							'click' => 'function(){ Sort.toNext(this); return false; }',
							'imageUrl' => 'image/sort_next.png'),
						'sort_last' => array(
							'label' => '置低',
							'url' => 'array("sortLast", "id" => $data->primaryKey)',
							'click' => 'function(){ Sort.toLast(this); return false; }',
							'imageUrl' => 'image/sort_last.png'),
						'sort_specify' => array(
							'label' => '指定位置',
							'url' => 'array("sortSpecify", "id" => $data->primaryKey)',
							'click' => 'function(){ Sort.toSpecify(this); return false; }',
							'imageUrl' => 'image/sort_specify.png'
						)
					),
					'visible' => Yii::app()->user->checkAccess('updatePage')
				),
				array(
					'name' => 'sort_order',
					'headerHtmlOptions' => array('style' => 'width:90px;'),	
					'htmlOptions' => array('align' => 'center'),
					'value' => 'CHtml::textField("sort_order_" . $data->primaryKey, $data->sort_order, array("class" => "sort-field"))',
					'type' => 'raw',
					'visible' => Yii::app()->user->checkAccess('updatePage')
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
							'visible' => 'Yii::app()->user->checkAccess("updatePage")'
						),
						'delete' => array(
							'visible' => 'Yii::app()->user->checkAccess("deletePage")'	
						)
					)
				)
			)
		));	?>

	</div>
</div>