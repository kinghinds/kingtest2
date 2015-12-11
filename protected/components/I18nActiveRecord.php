<?php

class I18nActiveRecord extends JI18nActiveRecord {
	
	protected $isReleasedFieldName = 'is_released';
	
	public function i18nLanguages() {
		return I18nHelper::getLanguageKeys();
	}
}

?>