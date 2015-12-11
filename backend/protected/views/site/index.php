<?php $this->beginClip('extraHead'); ?>
<?php echo CHtml::cssFile(Yii::app()->baseUrl
		. '/javascript/jquery.jqplot.1.0.0b2_r1012/dist/jquery.jqplot.css')
		. "\r\n"; ?>
<!--[if lt IE 9]>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl
		. '/javascript/jquery.jqplot.1.0.0b2_r1012/dist/excanvas.min.js')
		. "\r\n"; ?>
<![endif]-->
<?php echo CHtml::scriptFile(Yii::app()->baseUrl
		. '/javascript/jquery.jqplot.1.0.0b2_r1012/dist/jquery.jqplot.min.js')
		. "\r\n"; ?>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl
		. '/javascript/jquery.jqplot.1.0.0b2_r1012/dist/plugins/jqplot.json2.min.js')
		. "\r\n"; ?>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl
		. '/javascript/jquery.jqplot.1.0.0b2_r1012/dist/plugins/jqplot.dateAxisRenderer.min.js')
		. "\r\n"; ?>
<?php echo CHtml::scriptFile(Yii::app()->baseUrl
		. '/javascript/jquery.jqplot.1.0.0b2_r1012/dist/plugins/jqplot.highlighter.min.js')
		. "\r\n"; ?>
<script type="text/javascript">
<!--
var PREV_MONTH = '<?php echo date('Y-m-d', strtotime('-1 month')); ?>';
var CUR_MONTH = '<?php echo date('Y-m-d'); ?>';
var VIEW_URL = '<?php echo $this->createUrl("googleAnalytics"); ?>';

function getGoogleAnalytics() {
	$.ajax({
		type: 'POST',
		url: VIEW_URL,
		async : true,
		dataType: 'JSON',
		data: {
			rand: Math.random()
		},
		beforeSend: function() {
			$('#loading').show();
		},
		complete: function() {
			$('#loading').hide();
			$('#gac').show();
		},
		success: function(response) {
			if (response.result) {
				$.jqplot('gac', response.data, {
					title : 'Google Analytics（分析）',
					gridPadding : {
						right : 35
					},
					axes : {
						xaxis : {
							renderer : $.jqplot.DateAxisRenderer,
							tickOptions : {
								formatString : '%#m月%#d日'
							},
							min : PREV_MONTH,
							max : CUR_MONTH
						},
						yaxis : {
							pad : 1,
							min : 0
						}
					},
					highlighter : {
						show : true
					},
					series : [ {
						color : 'deepskyblue',
						label : 'Pageviews'
					}, {
						color : 'orange',
						label : 'Visits'
					} ],
					legend : {
						show : true
					}
				});	
			} else {
				alert(response.message);					
			}
		}
	});	
	return false;
}
//-->
</script>
<?php $this->endClip(); ?>
<div class="heading">
	<h1>
		<?php echo CHtml::image(Yii::app()->baseUrl . '/image/home.png'); ?>
		首页
	</h1>
</div>
<div class="content">
	<div class="overview">
		<h2>欢迎来到 <font color="#666666"><?php echo Yii::app()->name; ?></font> 管理中心</h2>
		<p>
			数据管理中心系由
			<a href="http://www.kinghinds.icoc.cc/" target="_blank">天下楚云</a>
			个人开发，并提供技术支持。
			<br />
			在这里，你可以轻松管理网站几乎所有的页面内容。如遇到任何问题，请点击
			<a href="mailto:759371065@qq.com">这里</a>
			告诉我们。
		</p>
		<ul>
			<?php if (Yii::app()->user->checkAccess('cleanCache')) { ?>
			<li>
				<?php echo CHtml::link('清除缓存', array('site/cleanCache')); ?>
			</li>
			<?php } ?>
			
			<?php if (Yii::app()->user->checkAccess('exportSqlFile')) { ?>
			<li><?php echo CHtml::link('备份数据库记录', array('exportSqlFile')); ?></li>
			<?php } ?>
		</ul>
		<div id="loading" style="display: none;">
			<?php echo CHtml::image(
		Yii::app()->baseUrl . '/image/loading.gif', '加载中');
			?>
		</div>
		<div id="gac" style="width:700px; height:300px;"></div>		
	</div>
</div>
