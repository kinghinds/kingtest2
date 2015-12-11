<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?><?php echo $model
		->isNewRecord ? '添加' : '修改';
																			   ?> Banner 位置</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#',
		array('class' => 'button', 'onclick' => "$('#form1').submit();return false;"));
		?>
		<?php echo CHtml::link('<span>取消</span>', array('index'),
		array('class' => 'button'));
		?>
	</div>
</div>

	<div class="content">
	<?php echo CHtml::errorSummary($model); ?>
	<?php echo CHtml::beginForm('', 'post',
		array('id' => 'form1', 'enctype' => 'multipart/form-data'));
	?>
	<table class="form" width="100%">
	    <tr>
	        <th width="160">
	            <?php echo CHtml::activeLabelEx($model, 'title'); ?>
	        </th>
	        <td>
	            <?php echo CHtml::activeTextField($model, 'title',
		array('size' => 80));
				?>
	        </td>
	    </tr>
	
	    <tr>
	        <th>
	            <?php echo CHtml::activeLabelEx($model, 'width'); ?>
	        </th>
	        <td>
	            <?php echo CHtml::activeTextField($model, 'width'); ?>
	            
	        </td>
	    </tr>
	    
	     <tr>
	        <th>
	            <?php echo CHtml::activeLabelEx($model, 'height'); ?>
	        </th>
	        <td>
	            <?php echo CHtml::activeTextField($model, 'height'); ?>
	            
	        </td>
	    </tr>
	
	</table>
	<?php echo CHtml::endForm(); ?>
</div>