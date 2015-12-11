<?php $this->beginClip('extraHead'); ?>
<?php Yii::app()->clientScript->registerCssFile(
		Yii::app()->clientScript->getCoreScriptUrl()
		. '/jui/css/base/jquery-ui.css'); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl . '/javascript/common.js') . "\r\n"; ?>
<style>
	.is_recommend{
		background: url("/images/is_commend.png") no-repeat scroll 99% 0 transparent;
	}
</style>
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
function recommendFn(){
	$('#form').attr('action','/backend/index.php?r=product/batchCommend');
	$('#form').removeAttr('onsubmit');
	$('#form').submit();
}
function unRecommendFn(){
	$('#form').attr('action','/backend/index.php?r=product/batchUnCommend');
	$('#form').removeAttr('onsubmit');
	$('#form').submit();
}
//-->
</script>
<?php $this->endClip(); ?>

<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>产品</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('createProduct')) { ?>
		<?php echo CHtml::link('<span>添加</span>', array('create'), array(
				'class' => 'button')); ?>
		<?php } ?>
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

		<?php echo CHtml::form(array('batchDelete'), 'post', array('id'=>"form",
			'onsubmit' => 'batchDelete("确认要删除选中数据吗?", this, "product_id[]"); return false;'
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
					'id' => 'product_id',
					'headerHtmlOptions' => array('style' => 'width:20px;'),
					'htmlOptions' => array('align' => 'center')
				),
				array(
					'name' => 'product_id',
					'headerHtmlOptions' => array('style' => 'width:80px;'),
					'htmlOptions' => array('align' => 'center')
				),
				array('name' => 'image_path',
										'header' =>'缩略图',
										'value' => 'CHtml::image($data->getThumbFileUrl(),$data->product_id,array("width"=>200,"height"=>100))',
										'type' => 'raw',
										'headerHtmlOptions' => array('style' => 'width:20%;'),
										'htmlOptions' => array(
												'class' => 'id-column',
												'align' => 'center')),
				array(
					'name' => 'name',
					'value' => '$data->getI18nColumn("name", true)',
					'evaluateHtmlOptions'=>true,  
					'htmlOptions' => array(
						'class'=>'"{$data->getClass($data->is_recommend)}"',
						),
					'type' => 'html'
				),
				array(
					'name' => 'series_id',
					'value' => '$data->series->title',
					'headerHtmlOptions' => array('style' => 'width:100px;'),
					'htmlOptions' => array('align' => 'center')
				),
				array(
					'name' => 'category_id',
					'value' => '$data->category->name',
					'headerHtmlOptions' => array('style' => 'width:100px;'),
					'htmlOptions' => array('align' => 'center')
				),
				array(
					'name' => 'brand_id',
					'value' => '$data->brand->title',
					'headerHtmlOptions' => array('style' => 'width:100px;'),
					'htmlOptions' => array('align' => 'center')
				),
				
				array(
					'class' => 'CButtonColumn',
					'header' => Product::model()->getAttributeLabel('sort_order'),
					'headerHtmlOptions' => array('style' => 'width:100px;'),
					'htmlOptions' => array('align' => 'center'),
					'template' => '{sort_first} {sort_previous} {sort_next} {sort_last} {sort_specify}',
					'buttons' => array(
						'sort_first' => array(
							'label' => '置顶',
							'url' => 'array("sortFirst", "id" => $data->primaryKey)',
							'click' => 'function(){ Sort.toFirst(this); return false; }',
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
					'visible' => Yii::app()->user->checkAccess('updateProduct')
				),
				array(
					'name' => 'sort_order',
					'header' => Product::model()->getAttributeLabel('sort_order') . '2',
					'headerHtmlOptions' => array('style' => 'width:90px;'),	
					'htmlOptions' => array('align' => 'center'),
					'value' => 'CHtml::textField("sort_order_" . $data->primaryKey, $data->sort_order, array("class" => "sort-field"))',
					'type' => 'raw',
					'visible' => Yii::app()->user->checkAccess('updateProduct')
				),
				array(
					'class' => 'CButtonColumn',
					'header' => '操作',
					'headerHtmlOptions' => array('style' => 'width:100px;'),
					'htmlOptions' => array('align' => 'center'),
					'template' => '{update} {delete} {common}',
					'buttons' => array(
						'common' =>array(
							'label'=> '首页推荐', 
							'imageUrl'=>Yii::app()->request->baseUrl.'/image/common.gif',
							'url' => 'array("commend", "id" => $data->primaryKey)',
						),
						
						'update' => array(
							'url' => 'array("update", "id" => $data->primaryKey, "return_url" => Yii::app()->request->url)',
							'visible' => 'Yii::app()->user->checkAccess("updateProduct")'
						),
						'delete' => array(
							'visible' => 'Yii::app()->user->checkAccess("deleteProduct")'	
						)
					)					
				)
			)
		)); ?>

		<?php if (Yii::app()->user->checkAccess('deleteProduct')) { ?>
		<input type="submit" value="删除选中项" />	
		<?php } ?>
		<input type="button" value="推荐选中项" onclick="recommendFn();" />
		<input type="button" value="取消推荐选中项" onclick="unRecommendFn();" />		
		<?php echo CHtml::endForm(); ?>

	</div>
</div>