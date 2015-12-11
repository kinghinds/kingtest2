<?php

class Product extends I18nActiveRecord {

	const UPLOAD_ORIGINAL_IMAGE_PATH = 'upload/product/orig/';
	const UPLOAD_LARGE_IMAGE_PATH = 'upload/product/large/';
	const UPLOAD_THUMBNAIL_IMAGE_PATH = 'upload/product/thumb/';

	public $productFile;
	public $deleteProductFile = false;

	public function i18nAttributes() {
		return array(
				'name',
				'content',
				'sub_content',
				'image_path', 
				'is_released', 
				'productFile',
				'deleteProductFile',
		);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{product}}';
	}

	public function attributeLabels() {
		return array(
				'product_id' => '#编号', 
				'name' => '产品名称',
				'series_id' => '所属系列',
				'category_id' => '产品所属分类',
				'brand_id' => '所属品牌',
				'sub_content' => '产品简介',
				'content' =>'产品内容',
				'image_path' => '产品图片', 
				'sort_order' => '排序',
				'is_recommend' => '推荐',
				'is_released' => '发布'
		);
	}

	public function rules() {
		return array(
			array('series_id','required'),
			array('name, sub_content, content,brand_id,category_id', 'safe'),
			array('sort_order', 'type', 'type' => 'integer'),
			array('is_released,is_recommend', 'type', 'type' => 'boolean'),
			array('productFile', 'file', 'allowEmpty' => true,
					'types' => 'jpg, jpeg, gif, png, swf'),
			array('productFile', 'validateImageFile'),
			array('deleteProductFile', 'type', 'type' => 'boolean'),
			array('i18nFormData', 'type', 'type' => 'array')
		);
	}

	public function relations() {
		return array(
			'category' => array(self::BELONGS_TO, 'ProductCategory',
					'category_id'),
			'series' => array(self::BELONGS_TO, 'ProductSeries',
					'series_id'),
			'brand' => array(self::BELONGS_TO, 'Brand',
					'brand_id'),
			'images' => array(self::HAS_MANY, 'ProductImage',
					'product_id'),
			);
	}

	public function validateImageFile($attribute, $params) {
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
		if ($this->productFile instanceof CUploadedFile
				&& $this->hasErrors('productFile') == false) {
			// 保存原文件
			$file = $this->productFile;
			$fileName = md5($file->tempName . uniqid()) . '.'
					. $file->extensionName;
			$filePath = Helper::mediaPath(self::UPLOAD_ORIGINAL_IMAGE_PATH
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

		} else if ($this->deleteProductFile) {
			// 删除图片
			@unlink(Helper::mediaPath(self::UPLOAD_ORIGINAL_IMAGE_PATH
					. $this->image_path, FRONTEND));
			@unlink(Helper::mediaPath(self::UPLOAD_THUMBNAIL_IMAGE_PATH
					. $this->image_path, FRONTEND));
			// 更新数据
			$this->updateByPk($this->primaryKey, array('image_path' => ''));
		}
	}

	protected function afterDelete() {
		parent::afterDelete();

		// 删除图片
		@unlink(Helper::mediaPath(self::UPLOAD_ORIGINAL_IMAGE_PATH
				. $this->image_path, FRONTEND));
		@unlink(Helper::mediaPath(self::UPLOAD_THUMBNAIL_IMAGE_PATH
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

	public function getClass($value){
		if($value){
			return 'is_recommend';
		}else{
			return '';
		}
		
	}

	public function getProductFileUrl() {
		$fileExt = CFileHelper::getExtension($this->image_path);
		if (in_array($fileExt, array('jpg', 'jpeg', 'gif', 'png'))) {
			return Helper::mediaUrl(self::UPLOAD_ORIGINAL_IMAGE_PATH 
					. $this->image_path, FRONTEND);
		} else {
			return Helper::mediaUrl(self::UPLOAD_ORIGINAL_IMAGE_PATH 
					. $this->image_path, FRONTEND);
		}
	}
	public function getThumbFileUrl() {
		$fileExt = CFileHelper::getExtension($this->image_path);
		if (in_array($fileExt, array('jpg', 'jpeg', 'gif', 'png'))) {
			return Helper::mediaUrl(self::UPLOAD_THUMBNAIL_IMAGE_PATH 
					. $this->image_path, FRONTEND);
		} else {
			return Helper::mediaUrl(self::UPLOAD_ORIGINAL_IMAGE_PATH 
					. $this->image_path, FRONTEND);
		}
	}
	public function getPermalink() {
		if (empty($this->slug) == false && strlen(trim($this->slug)) > 0) {
			return Yii::app()
					->createUrl('product/view', array('slug' => $this->slug));
		} else {
			return Yii::app()
					->createUrl('product/view', array('seriesid' => $this->series_id));
		}
	}
}

?>