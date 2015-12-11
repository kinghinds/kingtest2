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
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>查看Excel</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>返回回复管理</span>', array('answer/index'), array(
				'class' => 'button')); ?>
	</div>
</div>

<div class="content">
	<div class="clearfix">
	<div class="list-view" id="yw0">
		<table class="list tree-table">
			<thead>
			<tr>
				<th style="width:15%;" id="yw0_c1">#编号</th>
				<th style="width:30%;" id="yw0_c2">第一个名字</th>
				<th style="width:30%;" id="yw0_c3">第二个名字</th>
				<th id="yw0_c4">注册时间</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($stockList as $data){ ?>
			<tr class="odd">
				<td align="center"><?php echo $data['id'];?></td>
				<td align="center"><?php echo $data['fristname'];?></td>
				<td align="center"><?php echo $data['lastname'];?></td>
				<td align="center" width="15%"><?php echo $data['create_time'];?></td>
			</tr>
			<?php }?>
			</tbody>
			</table>

		</div>
	</div>
</div>