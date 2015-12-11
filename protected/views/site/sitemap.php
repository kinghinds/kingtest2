  
<div class="con_bg">
	<div class="content">
		<div class="sitemap">
			<h3 class="title"><?php echo $title;?></h3>
		   <? $this->widget('zii.widgets.CMenu', array(
					'items' => $items,
					'htmlOptions' => array('class' => 'sitelist')
			)); ?>
		</div>
	</div>
</div>

