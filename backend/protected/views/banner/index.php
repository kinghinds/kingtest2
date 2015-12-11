<?php $this->beginClip('extraHead'); ?>
<?php Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl . '/javascript/common.js'). "\r\n";?>

<script type="text/javascript">
$(function(){
	$('.secondary-sort').click(function(){
		getSecondarySort('<?php echo Yii::app()->createUrl("site/updateSortOrder");?>', 'ProductCategory', 
			'<?php echo Yii::app()->createUrl("productCategory/index");?>');
	});
});
</script>
								
<script type="text/javascript">
$(function() {
    $('.tree-table a.is-released-0').live('click', function() {
        $(this).attr('class', 'is-released-1');
        var m = $(this).attr('href').match(/#(\d+)(\w+)/);
        updateIsReleased('<?php echo $this->createUrl("updateIsReleased"); ?>', m[1], m[2], 1);
        return false;
    });
    
    $('.tree-table a.is-released-1').live('click', function() {
        $(this).attr('class', 'is-released-0');
        var m = $(this).attr('href').match(/#(\d+)(\w+)/);
        updateIsReleased('<?php echo $this->createUrl("updateIsReleased"); ?>', m[1], m[2], 0);
        return false;
    });
});
</script>
<?php $this->endClip(); ?>

<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>Banner</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>添加</span>', array('create'),
		array('class' => 'button'));
		?>
	</div>
</div>

<div class="content">
	<div class="filter-form">
		<?php echo CHtml::form(array(''), 'get');
		?>
		位置 <?php echo CHtml::dropDownList('banner_position_id',
		$bannerPositionId, $bannerPositionOptions, array('empty' => ''));
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
		<?php echo CHtml::form(array('multipleDelete'), 'post',
		array(
				'onsubmit' => 'multiDelete("确认要删除选中数据吗?", this, "banner_id[]"); return false;'));
		?>	
		<?php $this
		->widget('zii.widgets.grid.CGridView',
				array('htmlOptions' => array('class' => 'list-view'),
						'itemsCssClass' => 'list tree-table',
						'loadingCssClass' => 'list-view-loading',
						'dataProvider' => $dataProvider, 'selectableRows' => 2,
						'columns' => array(
								array('class' => 'CCheckBoxColumn',
										'id' => 'banner_id',
										'headerHtmlOptions' => array(
												'style' => 'width:20px;'),
										'htmlOptions' => array(
												'align' => 'center')),
								array('name' => 'banner_id',
										'headerHtmlOptions' => array(
												'class' => 'id-column',
												'style' => 'width:80px;'),
										'htmlOptions' => array(
												'class' => 'id-column',
												'align' => 'center')),
								array('name' => 'image_path',
										'header' =>'缩略图',
										'value' => 'CHtml::image($data->getLargeUrl(),$data->banner_id,array("width"=>200,"height"=>100))',
										'type' => 'raw',
										'headerHtmlOptions' => array('style' => 'width:20%;'),
										'htmlOptions' => array(
												'class' => 'id-column',
												'align' => 'center')),
								array('name' => 'title',
										'value' => '$data->getI18nColumn("title", true)',
										'type' => 'html'),
								array(
									'name' => 'banner_position_id',
									'value' => '$data->position->title',
									'headerHtmlOptions' => array('style' => 'width:200px;'),
									'htmlOptions' => array('align' => 'center')
								),
								
								array('class' => 'CButtonColumn',
										'header' => '操作',
										'headerHtmlOptions' => array(
												'style' => 'width:100px;'),
										'htmlOptions' => array(
												'align' => 'center'),
										'template' => '{update} {delete}')),
						'pager' => array('class' => 'CLinkPager',
								'cssFile' => false)));
		?>
		<input type="submit" value="删除选中项" />	
		<?php echo CHtml::endForm(); ?>
	</div>
</div>