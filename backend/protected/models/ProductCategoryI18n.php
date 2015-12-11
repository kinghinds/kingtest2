<?php

class ProductCategoryI18n extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{product_category_i18n}}';
	}
}

?>