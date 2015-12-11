<div class="search-keywords">
	关键词：
	<?php foreach ($items as $item) { ?>
	<?php echo CHtml::link($item['label'], $item['url']); ?>
	<?php } ?>
</div>