<?php

class BannerI18n extends CActiveRecord {
	
	public $bannerFile;
	public $deleteBannerFile = false;
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{banner_i18n}}';
	}
	
	public function validateBannerFile($attribute, $params) {
		if(!empty($this->bannerFile)){
			list($width, $height, $type, $attr) = getimagesize($this->bannerFile->tempName);
			if ($width != '1200' && $height != '20') {
				$this->addError($attribute, '请上传 1200*200 的广告图片！');
			}
		}
		if ($this->hasErrors($attribute) == false
				&& $this->$attribute instanceof CUploadedFile) {
			if ($this->$attribute->extensionName != 'swf') {
				list($width, $height, $type, $attr) = getimagesize(
						$this->$attribute->tempName);
				if (empty($width) || empty($height)) {
					$this->addError($attribute,
							$this->$attribute->name . ' 图片无法识别');
				}
			}
		}
	}
	
	protected function afterSave() {
		parent::afterSave();
		$this->validateBannerFile('bannerFile', null);
		require_once('Image.php');
		
		// 保存图片
		if ($this->hasErrors('bannerFile') == false
				&& $this->bannerFile instanceof CUploadedFile) {
			// 保存原图
			$file = $this->bannerFile;
			$fileName = md5($file->tempName . uniqid()) . '.'
					. $file->extensionName;
			$filePath = Helper::mediaPath(
					Banner::UPLOAD_LARGE_IMAGE_PATH . $fileName, FRONTEND);
			$file->saveAs($filePath);
			
			// 如果是图片需要进行裁切
		
			// 右侧底图
			if (strtolower($file->extensionName) != 'swf') {
				$image = new Image($filePath);
				$image
						->save(
								Helper::mediaPath(
										Banner::UPLOAD_LARGE_IMAGE_PATH
												. $fileName, FRONTEND));
			}

			
			// 更新数据
			$this->updateByPk($this->primaryKey,
					array(
							'banner_path' => $fileName
					));
			
		} else if ($this->deleteBannerFile) {
			@unlink(
					Helper::mediaPath(
							Banner::UPLOAD_LARGE_IMAGE_PATH
									. $this->banner_path, FRONTEND));
			
			// 更新数据
			$this->updateByPk($this->primaryKey,
					array(
							'banner_path' => ''
					));
		}
	}
	
	protected function afterDelete() {
		parent::afterDelete();
		
		// 删除图片
		@unlink(
				Helper::mediaPath(
						Banner::UPLOAD_LARGE_IMAGE_PATH . $this->banner_path,
						FRONTEND));
	}
}

?>