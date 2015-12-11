<?php

class CasesI18n extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{cases_i18n}}';
	}
}

?>