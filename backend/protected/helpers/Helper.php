<?php

class Helper {
	
	public static function slugFilter($slug) {
		$slug = html_entity_decode(strip_tags($slug));
		$slug = preg_replace('/\W/', '-', $slug);
		$slug = preg_replace('/(-+)/', '-', $slug);
		$slug = trim($slug, '-');
		return $slug;
	}
	
	public static function fieldTips($tips, $htmlOptions = array()) {
		if (!isset($htmlOptions['class']))
			$htmlOptions['class'] = 'field-tips';
		return CHtml::tag('div', $htmlOptions, $tips);
	}
	
	public static function getClassConst($class, $const) {
		return constant(sprintf('%s::%s', $class, $const));
	}
	
	public static function isIntegerExt($value) {
		return is_int($value) || ctype_digit($value);
	}
	
	public static function mediaUrl($url, $stage = STAGE) {
		switch ($stage) {
			case BACKEND:
				$baseUrl = Yii::app()->baseUrl;
				break;
			case FRONTEND:
				$baseUrl = Yii::app()->params['frontendBaseUrl'];
				break;
		}
		return $baseUrl . '/' . str_replace('\\', '/', $url);
	}

	public static function imageUrl($url, $stage = STAGE) {
		switch ($stage) {
			case BACKEND:
				$baseUrl = Yii::app()->params['frontendBaseUrl'];
				break;
			case FRONTEND:
				$baseUrl = Yii::app()->baseUrl;
				break;
		}
		return $baseUrl . '/' . str_replace('\\', '/', $url);
	}
	
	public static function mediaPath($path, $stage = STAGE) {
		switch ($stage) {
			case 'BACKEND':
				$rootPath = Yii::getPathOfAlias('webroot');
				break;
			case 'FRONTEND':
				$rootPath = Yii::getPathOfAlias('frontendBasePath') . '/..';
				break;
		}
		return $rootPath . '/' . str_replace('\\', '/', $path);
	}
	
	public static function getJuiDatePickerOptions() {
		return array(
				'dateFormat' => 'yy-mm-dd',
				'showAnim' => '',
		//'changeMonth'=>true,
		//'changeYear'=>true,
		);
	}
	
	public static function renderFlashHtml($url, $options = array()) {
		if (!isset($options['height']))
			$options['height'] = 240;
		if (!isset($options['width']))
			$options['width'] = 320;
		if (!isset($options['wmode']))
			$options['wmode'] = 'transparent';
		if (!isset($options['quality']))
			$options['quality'] = 'high';
		return Yii::app()->controller->renderPartial('/flash_template',
				array(
						'url' => $url,
						'options' => $options
				), true);
	}
	
	public static function getXHEditorOptions() {
		return array(
				'html5Upload' => false,
				'upLinkUrl' => Yii::app()->createUrl('site/xheditorUpload',
						array(
								'uploadType' => 'link'
						)),
				'upLinkExt' => 'zip,rar,txt',
				'upImgUrl' => Yii::app()->createUrl('site/xheditorUpload',
						array(
								'uploadType' => 'img'
						)),
				'upImgExt' => 'jpg,jpeg,gif,png',
				'upFlashUrl' => Yii::app()->createUrl('site/xheditorUpload',
						array(
								'uploadType' => 'flash'
						)),
				'upFlashExt' => 'swf',
				'upMediaUrl' => Yii::app()->createUrl('site/xheditorUpload',
						array(
								'uploadType' => 'media'
						)),
				'upMediaExt' => 'avi',
		);
	}
	
	/**
	 * @refer http://rameshify.com/blog/general/php-time_since-function-works-similar-to-twitter/
	 *
	 */
	// THIS METHOD WILL COMPUTE TIME DIFFERENCE AND RETURNS A STRING SPECIFYING THE TIME SINCE THE SPECIFIED TIMESTAMP
	// INPUT: $time REPRESENTING A TIMESTAMP
	// OUTPUT: A STRING SPECIFYING THE TIME SINCE THE SPECIFIED TIMESTAMP
	// NOTE: this script as it is, will return -1 if hours > 24, which means the difference is more than a day
	public static function time_since($time) {
		
		$now = time();
		$now_day = date("j", $now);
		$now_month = date("n", $now);
		$now_year = date("Y", $now);
		
		$time_day = date("j", $time);
		$time_month = date("n", $time);
		$time_year = date("Y", $time);
		$time_since = "";
		
		switch (TRUE) {
			
			case ($now - $time < 60):
			// RETURNS SECONDS
				$seconds = $now - $time;
				// Append "s" if plural
				$time_since = $seconds > 1 ? "$seconds seconds" : "$seconds second";
				break;
			case ($now - $time < 45 * 60): // twitter considers > 45 mins as about an hour, change to 60 for general purpose
			// RETURNS MINUTES
				$minutes = round(($now - $time) / 60);
				$time_since = $minutes > 1 ? "$minutes minutes" : "$minutes minute";
				break;
			case ($now - $time < 86400):
			// RETURNS HOURS
				$hours = round(($now - $time) / 3600);
				$time_since = $hours > 1 ? "about $hours hours" : "about $hours hour";
				break;
			// UNCOMMENT the following lines if you wish to use for general purpose other than twitter
			case ($now - $time < 1209600):
			// RETURNS DAYS
				$days = round(($now - $time) / 86400);
				$time_since = "$days days";
				break;
			case (mktime(0, 0, 0, $now_month - 1, $now_day, $now_year)
					< mktime(0, 0, 0, $time_month, $time_day, $time_year)):
			// RETURNS WEEKS
				$weeks = round(($now - $time) / 604800);
				$time_since = "$weeks weeks";
				break;
			case (mktime(0, 0, 0, $now_month, $now_day, $now_year - 1)
					< mktime(0, 0, 0, $time_month, $time_day, $time_year)):
			// RETURNS MONTHS
				if ($now_year == $time_year) {
					$subtract = 0;
				} else {
					$subtract = 12;
				}
				$months = round($now_month - $time_month + $subtract);
				$time_since = "$months months";
				break;
			default:
			// RETURNS YEARS
				if ($now_month < $time_month) {
					$subtract = 1;
				} elseif ($now_month == $time_month) {
					if ($now_day < $time_day) {
						$subtract = 1;
					} else {
						$subtract = 0;
					}
				} else {
					$subtract = 0;
				}
				$years = $now_year - $time_year - $subtract;
				$time_since = "$years years";
				break;
			
			default:
				$time_since = -1;
				break; //delete this line if you uncomment the above lines
				
		}
		
		if ($time_since == "0 years ago") {
			$time_since = "";
		}
		
		return $time_since;
		
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
	
	public static function cut($str, $len, $sy = '...') {
		$par = 0; // 截取结束位置
		$returnStrLen = 0; // 截取字符个数,2个英文字符=1个汉字距离!
		$strlen = strlen($str);
		if ($len > $strlen)
			return $str;
		for ($i = 0; $i < $len * 3; $i++) { // len*3效率会大大提高!
			if ($returnStrLen < $len) {
				$Str = substr($str, $par, 1);
				if (Ord($Str) > 127) {
					$par += 3; // UTF-8编码
					$returnStrLen += 1;
				} else {
					$par += 1;
					$returnStrLen += 0.5; // 1汉=2英宽度
				}
			} else
				break;
		}
		$out = substr($str, 0, $par);
		if (strlen($str) > strlen($out))
			$out .= $sy;
		return $out;
	}
	
	public static function getDatabase() {
		$sql = 'SELECT DATABASE()';
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
	
	public static function getTables() {
		$sql = 'SHOW TABLES FROM `' . self::getDatabase() . '`';
		return Yii::app()->db->createCommand($sql)->queryColumn();
	}

	public static function arrangeKeywords($keywords) {
		$keywords = trim($keywords);
		$keywords = str_replace('，', ',', $keywords);
		$keywordList = explode(',', $keywords);
		foreach ($keywordList as $i => $keyword) {
			$keywordList[$i] = trim($keyword);
		}
		$keywordList = array_unique($keywordList);
		return implode(',', $keywordList);
	}

	public static function curl_get_contents($url)   
    {   
        $ch = curl_init();   
        curl_setopt($ch, CURLOPT_URL, $url);            //设置访问的url地址   
        //curl_setopt($ch,CURLOPT_HEADER,1);            //是否显示头部信息   
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);           //设置超时   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果   
        $r = curl_exec($ch);   
        curl_close($ch);   
        return $r;   
    } 
}
