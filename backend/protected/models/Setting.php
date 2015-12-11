<?php

class Setting extends I18nActiveRecord {
	
	const UPLOAD_ORIGINAL_IMAGE_PATH = 'upload/editor/orig/';
	const UPLOAD_THUMBNAIL_IMAGE_PATH = 'upload/editor/thumb/';
	
	public $i18nFormData = array();
	
	public function i18nAttributes() {
		return array('value');
	}
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{setting}}';
	}
	
	public function rules() {
		return array(
				array('code, value', 'type', 'type' => 'string'),
				array('i18nFormData', 'type', 'type' => 'array')
		);
	}
	
	public static function getValueByCode($code, $isMultiLang = false) {
		if ($isMultiLang == true) {
			$model = Setting::model()->localized(Yii::app()->language, false)
					->findByAttributes(array('code' => $code));
		} else {
			$model = Setting::model()->findByAttributes(array('code' => $code));
		}
		return is_null($model) ? null : $model->value;
	}
}

?>