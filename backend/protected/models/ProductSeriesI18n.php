<?php

class ProductSeriesI18n extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{product_series_i18n}}';
	}
}

?>