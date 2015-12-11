// JavaScript Document
//分享到新浪微博
function share2sina(_url,_title,_pic){
			var url = "http://v.t.sina.com.cn/share/share.php",
			c = url + "?url=" + encodeURIComponent(_url) + "&title=" + _title + "&pic=" + _pic+"&searchPic=false";

			window.open(c, "shareSina", "height=480,width=608,top=100,left=200,toolbar=no,menubar=no,resizable=yes,location=yes,status=no");
		}

//分享到腾讯微博
function share2tx(_url,_title,_pic){
			var url = "http://v.t.qq.com/share/share.php",
			c = url + "?url=" + encodeURIComponent(_url) + "&title=" + _title + "&pic=" + _pic;

			window.open(c, "shareQQ", "height=480,width=608,top=100,left=200,toolbar=no,menubar=no,resizable=yes,location=yes,status=no");
		}
		
//分享到QQ空间
function share2QQ(_url,_title,_pic){
			var url = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey",
			c = url + "?url=" + encodeURIComponent(_url) + "&title=" + _title + "&pic=" + _pic;

			window.open(c, "shareQQ", "height=480,width=608,top=100,left=200,toolbar=no,menubar=no,resizable=yes,location=yes,status=no");
		}

//分享到人人网
function share2renren(_url,_title,_pic){
			var url = "http://widget.renren.com/dialog/share",
			
			c = url + "?resourceUrl=" + _url + "&image="+_pic+"&charset=GB2312";

			window.open(c, "shareRR", "height=480,width=608,top=100,left=200,toolbar=no,menubar=no,resizable=yes,location=yes,status=no");
		}

//分享到豆瓣
function share2douban(_url,_title,_pic){
			var url = "http://www.douban.com/recommend",
			
			c = url + "?url=" + _url + "&title="+_title+"&comment="+_title+"&image="+_pic;

			window.open(c, "shareDB", "height=480,width=608,top=100,left=200,toolbar=no,menubar=no,resizable=yes,location=yes,status=no");
		}