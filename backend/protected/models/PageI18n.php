<?php

class PageI18n extends CActiveRecord {
	public $moduleName;
	public $bannerFile;
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{page_i18n}}';
	}
	
	public function beforeSave() {
		// 保存 Banner
		if ($this->bannerFile instanceof CUploadedFile
				&& in_array(strtolower($this->bannerFile->extensionName),
						array(
								'swf',
								'jpg',
								'jpeg',
								'gif',
								'png'
						))) {
			// 保存原文件
			$file = $this->bannerFile;
			$fileName = md5($file->tempName . uniqid()) . '.'
					. $file->extensionName;
			list($width, $height, $type, $attr) = getimagesize($file->tempName);
			$htmlOptions = array(
					'width' => $width,
					'height' => $height,
			);
			$file->saveAs(
					Helper::mediaPath(Page::UPLOAD_BANNER_PATH . $fileName,
							FRONTEND));
			
			if ($this->moduleName == 'product')
				$htmlOptions['class'] = 'ban';
			
			// 生成 HTML 代码
			if (strtolower($file->extensionName) == 'swf') {
				$this->banner_section = Helper::renderFlashHtml(
						Helper::mediaUrl(Page::UPLOAD_BANNER_PATH . $fileName,
								FRONTEND), $htmlOptions);
			} else {
				$this->banner_section = CHtml::image(
						Helper::mediaUrl(Page::UPLOAD_BANNER_PATH . $fileName,
								FRONTEND), '', $htmlOptions);
			}
		}
		
		return parent::beforeSave();
	}
}

?>