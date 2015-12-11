<?php

class ProductImage extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{product_image}}';
	}

	public function rules() {
		return array(
				array('product_id, sort_order', 'type', 'type' => 'integer'),
				array('file_name, image_path', 'safe'),
				array('is_released', 'type', 'type' => 'boolean'));
	}

	public function getOriginalImageUrl() {
		return Helper::mediaUrl(
				Product::UPLOAD_ORIGINAL_IMAGE_PATH . $this->image_path,
				FRONTEND);
	}

	public function getLargeImageUrl() {
		return Helper::mediaUrl(
				Product::UPLOAD_LARGE_IMAGE_PATH . $this->image_path, FRONTEND);
	}

	public function getThumbnailImageUrl() {
		return Helper::mediaUrl(
				Product::UPLOAD_THUMBNAIL_IMAGE_PATH . $this->image_path,
				FRONTEND);
	}

	protected function afterDelete() {
		parent::afterDelete();

		@unlink(
				Helper::mediaPath(
						Product::UPLOAD_ORIGINAL_IMAGE_PATH . $this->image_path,
						FRONTEND));
		@unlink(
				Helper::mediaPath(
						Product::UPLOAD_LARGE_IMAGE_PATH . $this->image_path,
						FRONTEND));
		@unlink(
				Helper::mediaPath(
						Product::UPLOAD_THUMBNAIL_IMAGE_PATH
								. $this->image_path, FRONTEND));
	}
}

?>