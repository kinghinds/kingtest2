<?php

class Banner extends I18nActiveRecord {

	const UPLOAD_LARGE_IMAGE_PATH = 'upload/banner/large/';

	public $bannerFile;
	public $deleteBannerFile = false;

	public function i18nAttributes() {
		return array('title', 'sub_content', 'banner_path', 'is_released', 'bannerFile',
				'deleteBannerFile');
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{banner}}';
	}

	public function attributeLabels() {
		return array(
			'banner_id' 		 => '#编号', 
			'banner_position_id' => '位置',
			'title' 			 => '标题', 
			'sub_content'		 => '简介',
			'link_url' 			 => '链接地址',
			'banner_path' 		 => 'Banner', 
			'sort_order' 		 => '排序',
			'is_released' 		 => '发布'
		);
	}

	public function rules() {
		return array(
				array('title, link_url,sub_content', 'safe'),
				array('banner_position_id, sort_order', 'type', 'type' => 'integer'),
				array('is_released', 'type', 'type' => 'boolean'),
				array('bannerFile', 'file', 'allowEmpty' => true, 'types' => 'jpg, jpeg, gif, png, swf','maxSize'=>1024 * 1024 * 1, // 10MB 
               'tooLarge'=>'图片大小超过1MB，请重新上传一张更小的图片！',),
				array('bannerFile', 'validateBannerFile'),
				array('deleteBannerFile', 'type', 'type' => 'boolean'),
				array('i18nFormData', 'type', 'type' => 'array'));
	}
	public function relations() {
		return array(
			'position' => array(self::BELONGS_TO, 'BannerPosition',
					'banner_position_id'),
			
			);
	}
	public function validateBannerFile($attribute, $params) {
		if(!empty($this->bannerFile)){
			list($width, $height, $type, $attr) = getimagesize($this->bannerFile->tempName);
			if ($width != '1200' && $height != '200') {
				$this->addError($attribute, '请上传 1200*200 的广告图片！');
			}
		}
		if ($this->hasErrors($attribute) == false && $attribute instanceof CUploadedFile) {
			if ($attribute->extensionName != 'swf') {
				list($width, $height, $type, $attr) = getimagesize($attribute->tempName);

				if (empty($width) || empty($height)) {
					$this->addError($attribute, $$attribute->name . ' 图片无法识别');
				}
			}
		}
	}

	protected function afterSave() {
		parent::afterSave();
		require_once('Image.php');

		// 保存图片
		if ($this->bannerFile instanceof CUploadedFile
				&& $this->hasErrors('bannerFile') == false) {
			// 保存原文件
			$file = $this->bannerFile;
			$fileName = md5($file->tempName . uniqid()) . '.'
					. $file->extensionName;
					//list($width, $height, $type, $attr) = getimagesize($file->tempName);
			$filePath = Helper::mediaPath(
					self::UPLOAD_LARGE_IMAGE_PATH . $fileName, FRONTEND);
			$file->saveAs($filePath);

			if (strtolower($file->extensionName) != 'swf') {
				$image = new Image($filePath);
				$image->save(Helper::mediaPath(self::UPLOAD_LARGE_IMAGE_PATH . $fileName, FRONTEND));
			}
			// 更新数据
			$this->updateByPk($this->primaryKey,
							array('banner_path' => $fileName));

		} else if ($this->deleteBannerFile) {
			// 删除图片
			@unlink(
					Helper::mediaPath(
							self::UPLOAD_LARGE_IMAGE_PATH
									. $this->banner_path, FRONTEND));
			// 更新数据
			$this->updateByPk($this->primaryKey,
							array('banner_path' => ''));
		}
	}

	protected function afterDelete() {
		parent::afterDelete();

		// 删除图片
		@unlink(
				Helper::mediaPath(
						self::UPLOAD_LARGE_IMAGE_PATH . $this->banner_path,
						FRONTEND));
	}

	public function getMinSortOrder() {
		return (int) Yii::app()->db->createCommand()->select('MIN(sort_order)')
				->from($this->tableName())->queryScalar();
	}

	public function getPreviousSortOrder() {
		return (int) Yii::app()->db->createCommand()->select('sort_order')
				->from($this->tableName())->where('sort_order < :sort_order')
				->order('sort_order DESC')
				->bindValue(':sort_order', $this->sort_order)->queryScalar();
	}

	public function getNextSortOrder() {
		return (int) Yii::app()->db->createCommand()->select('sort_order')
				->from($this->tableName())->where('sort_order > :sort_order')
				->order('sort_order ASC')
				->bindValue(':sort_order', $this->sort_order)->queryScalar();
	}

	public function getMaxSortOrder() {
		return (int) Yii::app()->db->createCommand()->select('MAX(sort_order)')
				->from($this->tableName())->queryScalar();
	}

	public function getLargeUrl() {
		return Helper::mediaUrl(
				self::UPLOAD_LARGE_IMAGE_PATH . $this->banner_path,
				FRONTEND);
	}

	public function getLinkUrl() {
		if (preg_match('#^(http|https|ftp)://.+$#', $this->link_url)) {
			return $this->link_url;
		} else if (strpos($this->link_url, '/') === 0) {
			return Yii::app()->baseUrl . '/' . Yii::app()->language
					. $this->link_url;
		} else {
			return Yii::app()->baseUrl . '/' . Yii::app()->language . '/'
					. $this->link_url;
		}
	}
}

?>