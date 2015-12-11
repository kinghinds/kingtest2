<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- BEGIN html -->
<html xmlns="http://www.w3.org/1999/xhtml">
	<!-- BEGIN head -->
	<head>
		<title>PEBBLE</title>
		<!-- Meta Tags -->
		
		<meta name="description" content="" />
		<!-- Favicon -->
		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
		<!-- Stylesheets -->
		<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" id="main-style" />
		<link rel="stylesheet" type="text/css" href="css/demo-tool.css" media="screen" /><!-- Demo-tool is only demo -->
		<!-- Scripts -->
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script><!-- Cookie is only in demo -->
		<script type="text/javascript" src="js/slimScroll.min.js"></script>
		<script type="text/javascript" src="js/jquery.sexyslider.min.js"></script>
		<script type="text/javascript" src="js/doppio.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$('#slider').SexySlider({
				width  : 700,
				height : 322,
				strips : 10,
				delay  : 5000,
				effect : 'random',
				direction: 'random',
				navigation : '#navigation',
				control : '#control',
				titlePosition : 'bottom'
			});
		});
		
		$(function(){
			$('#sidebar-scroll').slimScroll({
				height: ($(window).height()-(64*2))+"px",
				start: $('#starting-article'),
			});
		});
		</script>
	<!-- END head -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
	<!-- BEGIN body -->
	<body>

		<!-- 选择网站风格 -->
		<?php $this->widget('SelectStyle') ?>
		<!-- 结束选择 -->

		<!-- BEGIN .wrapper -->
		<div class="wrapper">
		
			<!-- BEGIN .content -->
			<div class="content">
			
				<!-- BEGIN .header -->
				<div class="header">
					<a href="index.html"><img class="logo" src="images/logo.png" alt="" title="" /></a>
					<!--<a href="index.html"><h1 class="logo">Doppio Theme</h1></a>-->
					<!-- BEGIN .menu -->
					<ul class="menu">
						<li><a href="index.html">首&nbsp;&nbsp;&nbsp;&nbsp;页</a></li>
						<li><a href="#">关于我们</a></li>
						<li><a href="#">图库展示</a></li>
						<li><a href="contact.html">资信中心</a></li>
						<li class="view"><a href="feedback.html">咨询中心</a></li>
						<li><a href="contact.html">联系我们</a></li>
					<!-- END .menu -->
					</ul>
					
				<!-- END .header -->
				</div>
				 <?php echo $content; ?>
				 				
			<!-- END .content -->
			</div>
		
			<!-- BEGIN .sidebar -->
			<?php $this->widget('Sidebar') ?>

			<!-- END .sidebar -->
		
		<!-- END .wrapper -->
		</div>

		<script type="text/javascript">
		/* for demo-tool */
		if($.cookie("bgimg")) {
			var background = $.cookie("bgimg");
			document.body.style.background = "url("+(background)+") top center";
			document.getElementById('side-top').style.background = "url("+background+") top center";
			document.getElementById('side-bottom').style.background = "url("+background+") top center";
		};
		function show(id){
		$("#answer"+id).show(1000);
		};
		function hiddenAnswer(id){
		$("#answer"+id).hide(1000);
		}
		</script>
	<!-- END body -->
	</body>
<!-- END html -->
</html>