<?php

class Brand extends I18nActiveRecord {

	const UPLOAD_ORIGINAL_FILE_PATH = 'upload/brand/orig/';
	const UPLOAD_LARGE_IMAGE_PATH = 'upload/brand/large/';
	const UPLOAD_THUMBNAIL_IMAGE_PATH = 'upload/brand/thumb/';

	public $brandFile;
	public $deleteBrandFile = false;

	public function i18nAttributes() {
		return array(
				'title',
				'sub_content',
				'content',
				'image_path', 
				'is_released', 
				'brandFile',
				'deleteBrandFile',
		);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{brand}}';
	}

	public function attributeLabels() {
		return array(
				'brand_id' => '#编号', 
				'region_id' => '所属地区',
				'title' => '品牌名称',
				'content' =>'品牌详情',
				'sub_content' => '品牌简介',
				'region_id' => '所属地区',
				'image_path' => '品牌图片', 
				'sort_order' => '排序',
				'is_released' => '发布'
		);
	}

	public function rules() {
		return array(
			array('title, content', 'required'),
			array('region_id,sub_content,view_count','safe'),
			array('sort_order', 'type', 'type' => 'integer'),
			array('is_released', 'type', 'type' => 'boolean'),
			array('brandFile', 'file', 'allowEmpty' => true,
					'types' => 'jpg, jpeg, gif, png, swf'),
			array('brandFile', 'validateImageFile'),
			array('deleteBrandFile', 'type', 'type' => 'boolean'),
			array('i18nFormData', 'type', 'type' => 'array')
		);
	}

	public function relations() {
		return array(
			'region' => array(self::BELONGS_TO, 'Region',
					'region_id')
			
			
			);
	}
	public function getClass($value){
		if($value){
			return 'is_recommend';
		}else{
			return '';
		}
		
	}
	public static function getOptions() {
		$items = array();
		$models = self::model()->findAll();
		foreach ($models as $model) {
			$items[$model->primaryKey] = $model->title;
		}
		return $items;
	}
	public function validateImageFile($attribute, $params) {
		if(!empty($this->brandFile)){
			list($width, $height, $type, $attr) = getimagesize($this->brandFile->tempName);
			if ($width != '350' && $height != '230') {
				$this->addError($attribute, '请上传 350*230 的图片！');
			}
		}
		if ($this->hasErrors($attribute) == false
				&& $attribute instanceof CUploadedFile) {
			if ($attribute->extensionName != 'swf') {
				list($width, $height, $type, $attr) = getimagesize(
						$attribute->tempName);
				if (empty($width) || empty($height)) {
					$this->addError($attribute, $$attribute->name
							. ' 图片无法识别');
				}
			}
		}
	}

	protected function afterSave() {
		parent::afterSave();
		require_once('Image.php');

		// 保存图片
		if ($this->brandFile instanceof CUploadedFile
				&& $this->hasErrors('brandFile') == false) {
			// 保存原文件
			$file = $this->brandFile;
			$fileName = md5($file->tempName . uniqid()) . '.'
					. $file->extensionName;
			$filePath = Helper::mediaPath(self::UPLOAD_LARGE_IMAGE_PATH
					. $fileName, FRONTEND);
			$file->saveAs($filePath);
			// 如果是图片需要进行裁切
			if (strtolower($file->extensionName) != 'swf') {
				$image = new Image($filePath);
				$image->resize(350, 230)->save(Helper::mediaPath(
						self::UPLOAD_THUMBNAIL_IMAGE_PATH . $fileName, FRONTEND));
			}
			// 更新数据
			$this->updateByPk($this->primaryKey, array(
					'image_path' => $fileName));

		} else if ($this->deleteBrandFile) {
			// 删除图片
			@unlink(Helper::mediaPath(self::UPLOAD_LARGE_IMAGE_PATH
					. $this->image_path, FRONTEND));
			@unlink(Helper::mediaPath(Brand::UPLOAD_THUMBNAIL_IMAGE_PATH
				. $this->image_path, FRONTEND));

			// 更新数据
			$this->updateByPk($this->primaryKey, array('image_path' => ''));
		}
	}

	protected function afterDelete() {
		parent::afterDelete();
		@unlink(Helper::mediaPath(self::UPLOAD_LARGE_IMAGE_PATH
					. $this->image_path, FRONTEND));
		@unlink(Helper::mediaPath(Brand::UPLOAD_THUMBNAIL_IMAGE_PATH
				. $this->image_path, FRONTEND));
	}

	public function getMinSortOrder() {
		return (int) Yii::app()->db->createCommand()
				->select('MIN(sort_order)')
				->from($this->tableName())
				->queryScalar();
	}

	public function getPreviousSortOrder() {
		return (int) Yii::app()->db->createCommand()
				->select('sort_order')
				->from($this->tableName())
				->where('sort_order < :sort_order')
				->order('sort_order DESC')
				->bindValue(':sort_order', $this->sort_order)
				->queryScalar();
	}

	public function getNextSortOrder() {
		return (int) Yii::app()->db->createCommand()
				->select('sort_order')
				->from($this->tableName())
				->order('sort_order ASC')
				->where('sort_order > :sort_order')
				->bindValue(':sort_order', $this->sort_order)
				->queryScalar();
	}

	public function getMaxSortOrder() {
		return (int) Yii::app()->db->createCommand()
				->select('MAX(sort_order)')
				->from($this->tableName())
				->queryScalar();
	}

	public function getBrandFileUrl() {
		$fileExt = CFileHelper::getExtension($this->image_path);
		if (in_array($fileExt, array('jpg', 'jpeg', 'gif', 'png'))) {
			return Helper::mediaUrl(self::UPLOAD_LARGE_IMAGE_PATH 
					. $this->image_path, FRONTEND);
		} else {
			return Helper::mediaUrl(self::UPLOAD_LARGE_IMAGE_PATH 
					. $this->image_path, FRONTEND);
		}
	}
	public function getThumbFileUrl() {
		$fileExt = CFileHelper::getExtension($this->image_path);
		if (in_array($fileExt, array('jpg', 'jpeg', 'gif', 'png'))) {
			return Helper::mediaUrl(self::UPLOAD_THUMBNAIL_IMAGE_PATH 
					. $this->image_path, FRONTEND);
		} else {
			return Helper::mediaUrl(self::UPLOAD_THUMBNAIL_IMAGE_PATH 
					. $this->image_path, FRONTEND);
		}
	}

	public function getPermalink() {
		if (empty($this->slug) == false && strlen(trim($this->slug)) > 0) {
			return Yii::app()
					->createUrl('brand/view', array('slug' => $this->slug));
		} else {
			return Yii::app()
					->createUrl('brand/view', array('id' => $this->primaryKey));
		}
	}
}

?>