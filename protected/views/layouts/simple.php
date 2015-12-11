<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>深圳市云乐科技有限公司---深圳手机软件外包开发，iphone应用开发，ipad应用开发，android应用开发，主流手机平台（ios,android,wp等）应用软件系统的策划、研发、运营和推广，IM系统,LBS定位，手机游戏开发，CMS系统、CRM系统的定制服务，智能家居、节能设备、LED设备、智能玩具等的远程接入控制整体解决方案，Wifi无线路由器及其移动监控管理软件的整体解决方案</title> 
  <meta name="keywords" content="云乐科技,深圳手机软件外包,IOS开发,iPhone开发,iPad开发,Android开发,symbian,安卓外包,电子商务,SNS,IM系统,LBS定位,手机游戏开发,CMS,CRM,Wifi无线路由器,无线接入监控系统" />
<meta name="description" content="云乐科技---深圳手机软件外包，IOS开发,Android开发,IOS外包,安卓外包,SNS,IM系统,LBS定位,手机游戏开发,CMS系统,CRM系统的定制服务,远程接入控制,Wifi无线路由器及其移动监控管理软件" />
  <link rel="stylesheet" href="css/index.css" type="text/css"> 
  <link  rel="stylesheet" href="css/style.css" type="text/css" /> 
  <!--<script type="text/javascript" src="js/index.js"></script>-->
  <script type="text/javascript" src="js/jquery.js"></script> 
  <script type="text/javascript" src="js/jquery.easing.1.3.js"></script> 
  <script type="text/javascript" src="js/jquery.scrollTo-min.js"></script> 
  <script type="text/javascript" src="js/aktuals.js"></script>
  <script type="text/javascript">  
var browserEvent = function (obj, url, title) {  
    var e = window.event || arguments.callee.caller.arguments[0];  
    var B = {  
        IE : /MSIE/.test(window.navigator.userAgent) && !window.opera  
        , FF : /Firefox/.test(window.navigator.userAgent)  
        , OP : !!window.opera  
    };  
    obj.onmousedown = null;  
    if (B.IE) {  
        obj.attachEvent("onmouseup", function () {  
            try {  
                window.external.AddFavorite(url, title);  
                window.event.returnValue = false;  
            } catch (exp) {}  
        });  
    } else {  
        if (B.FF || obj.nodeName.toLowerCase() == "a") {  
            obj.setAttribute("rel", "sidebar"), obj.title = title, obj.href = url;  
        } else if (B.OP) {  
            var a = document.createElement("a");  
            a.rel = "sidebar", a.title = title, a.href = url;  
            obj.parentNode.insertBefore(a, obj);  
            a.appendChild(obj);  
            a = null;  
        }  
    }  
};  
</script> 
</head>

<body>
<div class="main_bg">
  <div class="header_bg"></div>
  <div class="content_bg"></div>
  <div class="bg_stretch1"></div>
  <!-- BEGIN main-->
  <div class="main">
  <div class="content_bg"></div>
  <div class="bg_stretch1"></div>
  <!--BEGIN header-->
    <?php $this->widget('Header'); ?>
    <!--END header-->
    </div>
    
    <!--BEGIN content-->
    <div class="content1">
    <div class="content_stretch1"></div>
    <div style="position:absolute; left:0px">
	<?php echo $content; ?>
	</div>    
    <!--END content-->
    </div>
	<?php $this->widget('Footer'); ?>
    </div>
    <!-- END main-->
    </div>
<!-- END main_bg-->
</div>
</body>
</html>