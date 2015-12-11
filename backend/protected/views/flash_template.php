<object
	codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0"
	width="<?php echo $options['width']; ?>"
	height="<?php echo $options['height']; ?>"
	classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
	<param name="movie" value="<?php echo $url; ?>" />
	<param name="quality" value="<?php echo $options['quality']; ?>" />
	<param name="wmode" value="<?php echo $options['wmode']; ?>" />
	<embed src="<?php echo $url; ?>"
		wmode="<?php echo $options['wmode']; ?>"
		quality="<?php echo $options['quality']; ?>"
		pluginspage="http://www.macromedia.com/go/getflashplayer"
		type="application/x-shockwave-flash"
		width="<?php echo $options['width']; ?>"
		height="<?php echo $options['height']; ?>"></embed> 
</object>
