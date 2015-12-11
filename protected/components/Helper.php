<?php

class Helper {
	public static function appOnBeginRequest() {
		if (Setting::getValueByCode('system_maintaining')) {
			header(
					'Location: ' . Yii::app()->baseUrl
							. '/systemMaintaining.html');
			exit();
		}
		
		/*
		 if (Yii::app()->language == 'en_us') {
		    header('Location: ' . Yii::app()->baseUrl . '/comingsoon.html');
		    exit;
		    }
		
		    if (Yii::app()->baseUrl . '/' == $_SERVER['REQUEST_URI']) {
		    Yii::app()->language = 'en_us';
		    // Yii::app()->language = 'zh_tw';
		    // Yii::app()->getRequest()->getPreferredLanguage();
		    }
		 */
	}
	
	public static function appOnEndRequest() {
		// self::crawler();
	}
	
	/*
	 public static function crawler() {
	 $agent_meta=$_SERVER['HTTP_USER_AGENT'];
	 $agent=null;
	 if(stripos($agent_meta,'Googlebot')!==false) {
	 $agent='Google';
	 } elseif(stripos($agent_meta,'Baiduspider')!==false) {
	 $agent='Baidu';
	 } elseif(stripos($agent_meta,'Yahoo')!==false) {
	 $agent='Yahoo';
	 } elseif(stripos($agent_meta,'msnbot')!==false) {
	 $agent='MSN';
	 }
	 if($agent) {
	 $access_url=Yii::app()->request->url;
	 $access_time=time();
	 Yii::app()->db->createCommand('INSERT INTO {{crawler}} (agent,agent_meta,access_url,access_time) VALUES (?,?,?,?)')->execute(array($agent,$agent_meta,$access_url,$access_time));
	 }
	 }
	 */
	
	public static function mediaPath($path, $stage = STAGE) {
		switch ($stage) {
			case 'BACKEND':
				$rootPath = Yii::getPathOfAlias('backendPath');
				break;
			case 'FRONTEND':
				$rootPath = Yii::getPathOfAlias('webroot');
				break;
		}
		return $rootPath . '/' . str_replace('\\', '/', $path);
	}
	
	public static function mediaUrl($url) {
		return Yii::app()->baseUrl . '/' . str_replace('\\', '/', $url);
	}
	
	/*
	 public static function mediaPath($path) {
	 return Yii::getPathOfAlias('webroot') . '/' . str_replace('\\', '/', $path);
	 }
	 */ 
	
	public static function emailStar($email) {
		$atPos = strpos($email, '@');
		if ($atPos > 2) {
			$prefix = substr($email, 0, 2) . str_repeat('*', $atPos - 2);
		} else {
			$prefix = str_repeat('*', $atPos);
		}
		return $prefix . substr($email, $atPos, strlen($email));
	}
	
	public static function changeFileSizeFormat($filesize) {
		if ($filesize >= 1073741824) {
			$filesize = round($filesize / 1073741824, 2) . ' Gb';
		} else if ($filesize >= 1048576) {
			$filesize = round($filesize / 1048576, 2) . ' Mb';
		} else if ($filesize >= 1024) {
			$filesize = round($filesize / 1024, 2) . ' Kb';
		} else {
			$filesize = $filesize . ' Bytes';
		}
		return $filesize;
	}
	
	public static function getGreet() {
		$hour = date('H');
		if ($hour > 6 && $hour < 10) {
			return Yii::t('common', 'Good morning');
		} else if ($hour > 10 && $hour < 18) {
			return Yii::t('common', 'Good afternoon');
		} else {
			return Yii::t('common', 'Good evening');
		}
	}

	public static function num2str($num) {
		$unit = array('', '十', '百', '千'); //定义一级单位数组
		$units = array('', '万', '亿', '兆'); //定义二级（万级）单位数组
		$n2s = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九'); //字义字符0~9对应的中文数组
		$s2 = strrev($num); //倒转字符串。
		$r = ""; //定义变量用于存储字符串的读法，初始化为空
		$i4 = -1; //定义万级单位数组的索引号，初始化为-1。其实也就是0，因为下面的循环刚开始执行时它就自增了1
		$zero = ""; //定义变量用于在座0字符的读法，初始化为空
		for ($i = 0, $len = strlen($s2); $i < $len; $i++) //开始执行循环，$i为索引号，表示字符串中字符的位置索引号（从零开始），可以使用$s2{$i}的形式访问字符串变量$s2中的第$i+1个字符
		{
			if ($i % 4 == 0) {  //首先判断万级单位，每隔四个字符就让万级单位数组索引号递增
				$i4++;
				$r = $units[$i4] . $r; //将万级单位存入该字符的读法中去，它肯定是放在当前字符读法的末尾，所以首先将它叠加入$r中，
				$zero= ""; //在万级单位位置的“0”肯定是不用的读的，所以设置零的读法为空
			}
			//处理0
			if ($s2{$i} == '0') //如果读出的字符是“0”，执行如下判断这个“0”是否读作“零”
			{
				switch ($i % 4) //
				{
					case 0:                                        
						break; //如果位置索引能被4整除，表示它所处位置是万级单位位置，这个位置的0的读法在前面就已经设置好了，所以这里直接跳过
					case 1:
					case 2:        
					case 3:        
						if ($s2{$i - 1} != '0') $zero = "零"; //如果不被4整除，那么都执行这段判断代码：如果它的下一位数字（针对当前字符串来说是上一个字符，因为之前执行了反转）也是0，那么跳过，否则读作“零”
						break;
				}
				$r = $zero . $r; //将“0”字符
				$zero = '';
			}
			else //如果不是“0”
			{
				$r = $n2s[intval($s2{$i})] . $unit[$i % 4] . $r; //就将该当字符转换成数值型并作为数组$n2s的索引号以得到与之对应的中文读法，其后再跟上它的的一级单位（空、十、百还是千）最后再加上前面已存入的读法内容。
			}
		}
		//处理前面的0
		$zPos = strpos($r, "零"); //得到字符读法中“零”所处的位置
		if ($zPos == 0 && $zPos !== false) $r = substr($r, 2, strlen($r) -2 ); //如果零出现在首位，那么就去除这个“零”和它后面的单位。
		return $r;
	}
}
?>