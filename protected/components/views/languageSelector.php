<?php foreach ($items as $item) { ?>
<?php 
if ($item['lang'] == $curLangLabel) {
	$current = ' class="current"';
} else {
	$current = '';
}

echo "<li".$current.">". CHtml::link($item['label'], $item['link_url']) . "</li>";?>
<?php } ?>