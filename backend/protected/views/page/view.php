<?php $this->beginClip('extraHead'); ?>
<?php Yii::app()->clientScript->registerCssFile(
			Yii::app()->clientScript->getCoreScriptUrl()
			. '/jui/css/base/jquery-ui.css'); ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php $this->endClip(); ?>

<div class="heading">
	<h1>
		<?php echo CHtml::image(Yii::app()->baseUrl . '/image/product.png'); ?>
		查看页面
	</h1>
	<div class="buttons">
		<?php if (Yii::app()->user->checkAccess('updatePage')) { ?>
		<?php if($viewId ==1) { ?>
		<?php echo CHtml::link('<span>修改</span>', array('update', 
				'id' => $page->page_id,'view_id'=>$viewId,'return_url' => $returnUrl), array(
				'class' => 'button')); ?>
		<?php }else{ ?>	
		<?php echo CHtml::link('<span>修改</span>', array('update', 
				'id' => $page->page_id,'return_url' => $returnUrl), array(
				'class' => 'button')); ?>
		<?php } ?>	
		<?php } ?>	
		<?php echo CHtml::link('<span>返回</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>
<div class="content">
	<table class="form" width="100%">	
		<tr>
			<th width="160"><?php echo CHtml::activeLabelEx($page, 'title'); ?></th>
			<td>
				<?php echo $page->title; ?>
			</td>
		</tr>
		<?php if($viewId !=1) { ?>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'parent_id'); ?></th>
			<td>
				<?php echo $page->parent_id != 0 ? $page->parent->title : "未设置" ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'module_name'); ?></th>
			<td>
				<?php echo $page->getDisplayModuleName($page->module_name, $page->module_template); ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'content'); ?></th>
			<td>
				<?php echo $page->content; ?>
			</td>
		</tr>
		<?php if($viewId !=1) { ?>
		<tr>
			<th><?php echo CHtml::activeLabel($page, 'bg_image_path'); ?></th>
			<td>
				<?php echo CHtml::image($page->getBgImageUrl(), '', array('style' => 'width:200px;')); ?>
			</td>
		</tr>
		
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'internal_link_keywords'); ?></th>
			<td>
				<?php echo $page->internal_link_keywords; ?><br />
				<?php echo $page->is_undisplay_ilk ==1 ? '是' : '否'; ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'search_keywords'); ?></th>
			<td>
				<?php echo $page->search_keywords; ?>
			</td>
		</tr>
		<?php } ?>
		
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'slug'); ?></th>
			<td>
				<?php echo $page->slug;	?>
				
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'head_title'); ?></th>
			<td>
				<?php echo $page->head_title; ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'meta_keywords'); ?></th>
			<td>
				<?php echo $page->meta_keywords; ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'meta_description'); ?></th>
			<td>
				<?php echo $page->meta_description;	?>
			</td>
		</tr>			
		<tr style="display:none;">
			<th><?php echo CHtml::activeLabelEx($page, 'banner_section'); ?></th>
			<td>
				<?php echo $page->banner_section; ?>
			</td>
		</tr>
		<tr style="display:none;">
			<th><?php echo CHtml::activeLabelEx($page, 'bannerFile'); ?></th>
			<td>
				<?php echo $page->bannerFile; ?>
			</td>
		</tr>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'link_url'); ?></th>
			<td>
				<?php echo $page->link_url;	?>
				<?php echo $page->target_window ? '新窗口打开' : '本窗口打开'; ?>
			</td>
		</tr>
		<?php if($viewId !=1) { ?>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'is_indexable'); ?></th>
			<td><?php echo $page->is_indexable == 1 ? '是' : '否'; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th><?php echo CHtml::activeLabelEx($page, 'is_released'); ?></th>
			<td><?php echo $page->is_released == 1 ? '是' : '否'; ?></td>
		</tr>
	
	</table>
</div>
