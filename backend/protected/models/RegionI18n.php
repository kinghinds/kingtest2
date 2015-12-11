<?php

class RegionI18n extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{region_i18n}}';
	}
}

?>