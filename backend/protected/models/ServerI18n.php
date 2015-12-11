<?php

class ServerI18n extends CActiveRecord {
	
	public $serverFile;
	public $deleteServerFile = false;

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{server_i18n}}';
	}
	
	public function validateServerFile($attribute, $params) {
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
		$this->validateServerFile('serverFile', null);
		require_once('Image.php');
		
		// 保存图片
		if ($this->hasErrors('serverFile') == false
				&& $this->serverFile instanceof CUploadedFile) {
			// 保存原图
			$file = $this->serverFile;
			$fileName = md5($file->tempName . uniqid()) . '.'
					. $file->extensionName;
			$filePath = Helper::mediaPath(Server::UPLOAD_ORIGINAL_FILE_PATH
					. $fileName, FRONTEND);
			$file->saveAs($filePath);
			
			// 如果是图片需要进行裁切
						
			if (strtolower($file->extensionName) != 'swf') {
				// 裁切大图
				// 位置不同图片尺寸不同
				$image = new Image($filePath);
				$image->resize(350, 230)->save(
						Helper::mediaPath(
								Server::UPLOAD_THUMBNAIL_IMAGE_PATH . $fileName,
								FRONTEND));
			}
			

			// 更新数据
			$this->updateByPk($this->primaryKey, array('image_path' => $fileName));
			
		} else if ($this->deleteServerFile) {
			// 删除图片
			@unlink(Helper::mediaPath(Server::UPLOAD_ORIGINAL_FILE_PATH
					. $this->image_path, FRONTEND));
			
			@unlink(Helper::mediaPath(Server::UPLOAD_THUMBNAIL_IMAGE_PATH
					. $this->image_path, FRONTEND));
			
			// 更新数据
			$this->updateByPk($this->primaryKey, array('image_path' => ''));
		}
	}
	
	protected function afterDelete() {
		parent::afterDelete();

		@unlink(Helper::mediaPath(Server::UPLOAD_ORIGINAL_FILE_PATH
				. $this->image_path, FRONTEND));
		@unlink(Helper::mediaPath(Server::UPLOAD_THUMBNAIL_IMAGE_PATH
				. $this->image_path, FRONTEND));
	}
}

?>