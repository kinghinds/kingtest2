<?php

class Server extends I18nActiveRecord {

	const UPLOAD_ORIGINAL_FILE_PATH = 'upload/server/orig/';
	const UPLOAD_LARGE_IMAGE_PATH = 'upload/server/large/';
	const UPLOAD_THUMBNAIL_IMAGE_PATH = 'upload/server/thumb/';

	public $serverFile;
	public $deleteServerFile = false;

	public function i18nAttributes() {
		return array(
				'name',
				'content',
				'sub_content',
				'image_path', 
				'is_released', 
				'serverFile',
				'deleteServerFile',
		);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{server}}';
	}

	public function attributeLabels() {
		return array(
				'server_id' => '#编号', 
				'name' => '服务名称',
				'sub_content' => '服务简介',
				'content' =>'服务内容',
				'image_path' => '服务图片', 
				'sort_order' => '排序',
				'is_released' => '发布'
		);
	}

	public function rules() {
		return array(
			array('name','required'),
			array('sub_content, content', 'safe'),
			array('sort_order', 'type', 'type' => 'integer'),
			array('is_released', 'type', 'type' => 'boolean'),
			array('serverFile', 'file', 'allowEmpty' => true,
					'types' => 'jpg, jpeg, gif, png, swf'),
			array('serverFile', 'validateImageFile'),
			array('deleteServerFile', 'type', 'type' => 'boolean'),
			array('i18nFormData', 'type', 'type' => 'array')
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
		if ($this->serverFile instanceof CUploadedFile
				&& $this->hasErrors('serverFile') == false) {
			// 保存原文件
			$file = $this->serverFile;
			$fileName = md5($file->tempName . uniqid()) . '.'
					. $file->extensionName;
			$filePath = Helper::mediaPath(self::UPLOAD_ORIGINAL_FILE_PATH
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

		} else if ($this->deleteServerFile) {
			// 删除图片
			@unlink(Helper::mediaPath(self::UPLOAD_ORIGINAL_FILE_PATH
					. $this->image_path, FRONTEND));

			// 更新数据
			$this->updateByPk($this->primaryKey, array('image_path' => ''));
		}
	}

	protected function afterDelete() {
		parent::afterDelete();

		// 删除图片
		@unlink(Helper::mediaPath(self::UPLOAD_ORIGINAL_FILE_PATH
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

	public function getServerFileUrl() {
		$fileExt = CFileHelper::getExtension($this->image_path);
		if (in_array($fileExt, array('jpg', 'jpeg', 'gif', 'png'))) {
			return Helper::mediaUrl(self::UPLOAD_ORIGINAL_FILE_PATH 
					. $this->image_path, FRONTEND);
		} else {
			return Helper::mediaUrl(self::UPLOAD_ORIGINAL_FILE_PATH 
					. $this->image_path, FRONTEND);
		}
	}
	public function getThumbFileUrl() {
		$fileExt = CFileHelper::getExtension($this->image_path);
		if (in_array($fileExt, array('jpg', 'jpeg', 'gif', 'png'))) {
			return Helper::mediaUrl(self::UPLOAD_THUMBNAIL_IMAGE_PATH 
					. $this->image_path, FRONTEND);
		} else {
			return Helper::mediaUrl(self::UPLOAD_ORIGINAL_FILE_PATH 
					. $this->image_path, FRONTEND);
		}
	}
	public function getPermalink() {
		if (empty($this->slug) == false && strlen(trim($this->slug)) > 0) {
			return Yii::app()
					->createUrl('server/view', array('slug' => $this->slug));
		} else {
			return Yii::app()
					->createUrl('server/view', array('id' => $this->primaryKey));
		}
	}

	public function getClass($value){
		if($value){
			return 'is_recommend';
		}else{
			return '';
		}
		
	}
}

?>