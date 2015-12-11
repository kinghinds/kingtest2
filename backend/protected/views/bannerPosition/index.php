<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>Banner 位置</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>添加</span>', array('create'),
		array('class' => 'button'));
		?>
	</div>
</div>

<div class="content">
	<div class="clearfix">
	<?php $this
		->widget('zii.widgets.grid.CGridView',
				array('htmlOptions' => array('class' => 'list-view'),
						'itemsCssClass' => 'list tree-table',
						'loadingCssClass' => 'list-view-loading',
						'dataProvider' => $dataProvider, 'selectableRows' => 0,
						'columns' => array(
								array('name' => 'banner_position_id',
										'headerHtmlOptions' => array(
												'class' => 'id-column',
												'style' => 'width:80px;'),
										'htmlOptions' => array(
												'class' => 'id-column',
												'align' => 'center')),
								array('name' => 'title'), /*
														  array('class' => 'CButtonColumn',
														          'header' => '操作',
														          'headerHtmlOptions' => array(
														                  'style' => 'width:100px;'),
														          'htmlOptions' => array(
														                  'align' => 'center'),
														          'template' => '{update} {delete}')*/)));
	?>
	</div>
</div>