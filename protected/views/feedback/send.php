<!-- BEGIN .main -->
<div class="main">
	<img src="images/1.jpg" alt="" title="" />
	<div class="article-span"></div>
	<h1>提交新问题</h1>
	<p>请注意，请尽量保证你的Email是真实可用的，这样才能方便我们更好的联系到你，更快的为你服务。同时，请简要叙述你遇到的问题。</p>
	
	<div class="spacer"></div>
	<div class="comment-form">
	<font color="red">
	<?php echo CHtml::errorSummary($feedbackForm); ?></font>
		<?php echo CHtml::beginForm('', 'post', array('enctype' => 'multipart/form-data')); ?>
			<?php echo CHtml::activeTextField($feedbackForm, 'name', array('placeholder' => '你的名字' ,'type'=>'text')); ?>
			<?php echo CHtml::activeTextField($feedbackForm, 'email', array('placeholder' => '你的E-mail' ,'type'=>'text')); ?>
			<?php echo CHtml::activeTextField($feedbackForm, 'title', array('placeholder' => '问题标题' ,'type'=>'text','style'=>'margin-right:0px;')); ?>
			<?php echo CHtml::activeTextArea($feedbackForm, 'content',
			array('rows' => '4', 'class' => 'textarea w5' ,'placeholder'=>'详细内容'));?>
			<input type="submit" name="send-comment" value="提交" />
			<div class="clear-float"></div>
		<?php echo CHtml::endForm(); ?>
	<!-- END .comment-form -->
	</div>
<!-- END .main -->
</div>

