<?php

class I18nActiveRecord extends JI18nActiveRecord {
	
	protected $dropI18nDataAfterDelete = true;
	protected $isReleasedFieldName = 'is_released';
	
	public $fieldForExtraErrors;
	
	public $i18nFormData = array();
	
	public function getI18nAttributes() {
		return $this->_i18nAttributes;
	}
	
	protected function afterConstruct()	{
		parent::afterConstruct();
		$this->i18nFormData = $this->_i18nAttributes;
	}

	public function beforeSave() {
		$this->_i18nAttributes = array_merge($this->_i18nAttributes,
				$this->i18nFormData);
		if ($this->hasEventHandler('onBeforeSave'))
			return $this->onBeforeSave(new CEvent($this));
		return true;
	}

	protected function afterFind() {
		parent::afterFind();
		$this->i18nFormData = $this->_i18nAttributes;
	}
	
	public static function getFilterOptions() {
	}
	
	public function i18nLanguages() {
		return I18nHelper::getFrontendLanguageKeys();
	}
	
	public function sourceLanguage() {
		return I18nHelper::getFrontendSourceLanguage();
	}
	
	public function getI18nColumn($columnName, $withSwitch = false) {
		$languages = I18nHelper::getFrontendLanguages(false);
		$sourceLanguageKey = current(array_keys($languages));
		$sourceLanguage = array_shift($languages);
		$id = $this->getPrimaryKey();
		$ret = $this->i18nLanguages() ? '[' . $sourceLanguage['short_label']
				. '] <span style="height: 16px; overflow-y: hidden;" title="'
				. $this->$columnName . '">' . $this->$columnName . '</span>' : '<span style="height: 16px; overflow-y: hidden;" title="'
				. $this->$columnName . '">' . $this->$columnName . '</span>';
		if ($withSwitch)
			$ret = '<a href="#' . $id . $sourceLanguageKey
					. '" class="is-released-' . $this->is_released . '"></a>'
					. $ret;
		foreach ($languages as $lang => $prop) {
			$ret .= '<br />';
			$model = $this->localized($lang, false)->findByPk($this->primaryKey);
			if ($withSwitch)
				$ret .= '<a href="#' . $id . $lang . '" class="is-released-'
						. intval($model->{'is_released'}) . '"></a>';
			$ret .= '[' . $prop['short_label'] . '] ' . $model->{$columnName};
		}
		return $ret;
	}
}
