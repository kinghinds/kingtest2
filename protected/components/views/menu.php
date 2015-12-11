
<ul id="nav">
	<li><a href="<?php echo CHtml::normalizeUrl(array('site/index')); ?>" class="<?php if(Yii::app()->controller->id == 'site'){echo 'br-red';}else{ echo 'br-blue';} ?>"><i class="icon-home"></i> <?php echo Yii::t('common', '首页'); ?></a></li>
	<li><a href="<?php echo CHtml::normalizeUrl(array('product/index')); ?>" class="<?php if(Yii::app()->controller->id == 'product'){echo 'br-red';}else{ echo 'br-blue';} ?>"><i class="icon-barcode"></i> <?php echo Yii::t('common', '产品中心'); ?></a></li>
	<li><a href="<?php echo CHtml::normalizeUrl(array('brand/index')); ?>" class="<?php if(Yii::app()->controller->id == 'brand'){echo 'br-red';}else{ echo 'br-blue';} ?>"><i class="icon-heart-empty"></i> <?php echo Yii::t('common', '品牌中心'); ?></a></li>
	<li><a href="<?php echo CHtml::normalizeUrl(array('server/index')); ?>" class="<?php if(Yii::app()->controller->id == 'server'){echo 'br-red';}else{ echo 'br-blue';} ?>"><i class="icon-user"></i> <?php echo Yii::t('common', '服务中心'); ?></a></li>
	<li><a href="<?php echo CHtml::normalizeUrl(array('feedback/index')); ?>" class="<?php if(Yii::app()->controller->id == 'feedback'){echo 'br-red';}else{ echo 'br-blue';} ?>"><i class="icon-envelope-alt"></i> <?php echo Yii::t('common', '反馈中心'); ?></a></li>
</ul>