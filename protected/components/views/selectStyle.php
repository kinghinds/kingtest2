<!-- BEGIN .demo-tool -->
<div onmouseover="javascript:demoTool(this, true);" onmouseout="javascript:demoTool(this, false);" class="demo-tool">
	<div class="comelink"></div>
	
	<div>网站背景</div>
	<a href="#" onclick="javascript:changeBg(this, 'bg.jpg');" id="tool-bg1" class="change-bg" style="background:url(images/bg.jpg);"></a>
	<a href="#" onclick="javascript:changeBg(this, 'bg-2.png');" id="tool-bg2" class="change-bg" style="background:url(images/bg-2.png);"></a>
	<a href="#" onclick="javascript:changeBg(this, 'bg-3.png');" id="tool-bg3" class="change-bg" style="background:url(images/bg-3.png);"></a>
	<a href="#" onclick="javascript:changeBg(this, 'bg-4.png');" id="tool-bg4" class="change-bg" style="background:url(images/bg-4.png);"></a>
	<div class="split"></div>
	
	<div>颜色板</div>
	<a href="#" onclick="javascript:changeColor(this, 'style.css');" id="tool-color1" class="change-color" style="background:#542c79;"></a>
	<a href="#" onclick="javascript:changeColor(this, 'style-blue.css');" id="tool-color2" class="change-color" style="background:#2e5688;"></a>
	<a href="#" onclick="javascript:changeColor(this, 'style-brown.css');" id="tool-color3" class="change-color" style="background:#9f4830;"></a>
	<a href="#" onclick="javascript:changeColor(this, 'style-green.css');" id="tool-color4" class="change-color" style="background:#679f30;"></a>

	<script type="text/javascript">
	/* for demo-tool */
	if($.cookie("css")) {
		if($.cookie("css") == "css/style.css")
			document.getElementById('tool-color1').className = "change-color active";
		else if($.cookie("css") == "css/style-blue.css")
			document.getElementById('tool-color2').className = "change-color active";
		else if($.cookie("css") == "css/style-brown.css")
			document.getElementById('tool-color3').className = "change-color active";
		else if($.cookie("css") == "css/style-green.css")
			document.getElementById('tool-color4').className = "change-color active";
	}
	
	if($.cookie("bgimg")) {
		if($.cookie("bgimg") == "images/bg.jpg")
			document.getElementById('tool-bg1').className = "change-bg active";
		else if($.cookie("bgimg") == "images/bg-2.png")
			document.getElementById('tool-bg2').className = "change-bg active";
		else if($.cookie("bgimg") == "images/bg-3.png")
			document.getElementById('tool-bg3').className = "change-bg active";
		else if($.cookie("bgimg") == "images/bg-4.png")
			document.getElementById('tool-bg4').className = "change-bg active";
	}
	</script>
<!-- END .demo-tool -->
</div>