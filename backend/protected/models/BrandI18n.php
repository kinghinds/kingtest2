<?php

class BrandI18n extends CActiveRecord {
	
	public $brandFile;
	public $deleteBrandFile = false;

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{brand_i18n}}';
	}
	
	public function validateBrandFile($attribute, $params) {
		if(!empty($this->brandFile)){
			list($width, $height, $type, $attr) = getimagesize($this->brandFile->tempName);
			if ($width != '350' && $height != '230') {
				$this->addError($attribute, '请上传 350*230 的图片！');
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
		$this->validateBrandFile('brandFile', null);
		require_once('Image.php');
		
		// 保存图片
		if ($this->hasErrors('brandFile') == false
				&& $this->brandFile instanceof CUploadedFile) {
			// 保存原图
			$file = $this->brandFile;
			$fileName = md5($file->tempName . uniqid()) . '.'
					. $file->extensionName;
			$filePath = Helper::mediaPath(Brand::UPLOAD_LARGE_IMAGE_PATH
					. $fileName, FRONTEND);
			$file->saveAs($filePath);
			// 如果是图片需要进行裁切
			if (strtolower($file->extensionName) != 'swf') {
				$image = new Image($filePath);
				$image->resize(350, 230)->save(Helper::mediaPath(
						Brand::UPLOAD_THUMBNAIL_IMAGE_PATH . $fileName, FRONTEND));
			}
			// 更新数据
			$this->updateByPk($this->primaryKey, array('image_path' => $fileName));
			
		} else if ($this->deleteBrandFile) {
			// 删除图片
			@unlink(Helper::mediaPath(Brand::UPLOAD_LARGE_IMAGE_PATH
					. $this->image_path, FRONTEND));
			@unlink(Helper::mediaPath(Brand::UPLOAD_THUMBNAIL_IMAGE_PATH
				. $this->image_path, FRONTEND));
			// 更新数据
			$this->updateByPk($this->primaryKey, array('image_path' => ''));
		}
		
	}
	
	protected function afterDelete() {
		parent::afterDelete();
		
		@unlink(Helper::mediaPath(Brand::UPLOAD_LARGE_IMAGE_PATH
				. $this->image_path, FRONTEND));
		@unlink(Helper::mediaPath(Brand::UPLOAD_THUMBNAIL_IMAGE_PATH
				. $this->image_path, FRONTEND));
	}

	public static function getFirstCode() {
		$criteria = new CDbCriteria();
		$criteria->compare('is_released', 1);
		$models = self::model()->findAll($criteria);
		$items = array();
		foreach ($models as $model) {
			$title = $model->title;
			$str = strtoupper($title{0});
			$items[$model->primaryKey] = $str;
		}
		sort($items);
		$items = array_flip(array_flip($items));
		return $items;
	}
}

?>