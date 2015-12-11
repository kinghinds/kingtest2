<?php

class SettingI18n extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{setting_i18n}}';
	}
}

?>