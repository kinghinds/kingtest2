<?php

/**
 * JI18nActiveRecord class file.
 *
 * @reference multilingual-active-record <http://www.yiiframework.com/extension/multilingual-active-record/> <created by guillemc>
 *
 * @author jerry2801 <jerry2801@gmail.com>
 *
 * @version alpha8 (2011-3-8 15:33)
 * @version alpha7 (2010-11-4 13:10)
 * @version alpha6 (2010-10-25 13:52)
 * @version alpha5 (2010-5-11 11:26)
 * @version alpha4 (2010-4-30 10:09)
 * @version alpha3
 *
 * A typical usage of I18nActiveRecord is as follows:
 * <pre>
 * class Product extends JI18nActiveRecord {
 *     public function i18nLanguages() {
 *         return array('zh_cn');
 *     }
 *     public function i18nAttributes() {
 *         return array('name','content');
 *     }
 * }
 * </pre>
 */
class JI18nActiveRecord extends CActiveRecord {
	protected $dropI18nDataAfterDelete=false;
	protected $isReleasedFieldName;

	protected $_i18nAttributes=array();

	public function __get($name) {
		if(array_key_exists($name,$this->_i18nAttributes))
		return $this->_i18nAttributes[$name];
		else
		return parent::__get($name);
	}

	public function __set($name,$value) {
		if(array_key_exists($name,$this->_i18nAttributes))
		$this->_i18nAttributes[$name]=$value;
		else
		parent::__set($name,$value);
	}

	public function __isset($name) {
		if(isset($this->_i18nAttributes[$name]))
		return true;
		else
		return parent::__isset($name);
	}

	public function i18nAttributes() {
		throw new CException('i18nAttributes() is required.');
	}

	public function i18nLanguages() {
		throw new CException('i18nLanguages() is required.');
	}

	public static function sourceLanguageKey() {
		//return Yii::app()->sourceLanguage;
		$languages=include dirname(__FILE__).'/../../../protected/config/languages.php';
		return current(array_keys($languages));
	}

	public function i18nClassName() {
		return get_class($this).'I18n';
	}

	public function langForeignKey() {
		return 'owner_id';
	}

	public function langField() {
		return 'lang';
	}

	public function localized($lang = null, $onlyReleased = true) {
		if (is_null($lang)) $lang = Yii::app()->language;
		if ($lang == self::sourceLanguageKey()) {
			if ($onlyReleased && $this->isReleasedFieldName) {
				$this->getDbCriteria()->mergeWith(array('condition' => 't.' . $this->isReleasedFieldName . '=1'));
			}
			return $this;
		} else {
			//$class = self::HAS_MANY;
			$class = self::HAS_ONE;
			//$options = array('index' => $this->langField());
			$condition = 'localized.' . $this->langField() . '=\'' . $lang . '\'';
			if ($onlyReleased && $this->isReleasedFieldName) {
				$condition .= ' AND localized.' . $this->isReleasedFieldName . '=1';
			}
			$options['condition'] = $condition;
			$this->getMetaData()->relations['localized'] = new $class(
				'localized', $this->i18nClassName(), $this->langForeignKey(), $options); 
			return $this->with('localized');
		}
	}

	public function multilingual($lang = null) {
		if(!$this->i18nLanguages())
		return $this;
		$class=self::HAS_MANY;
		$options=array('index'=>$this->langField());
		//$options['condition'] = 'multilingual.' . $this->langField() . '=\'' . Yii::app()->language . '\'';
		$this->getMetaData()->relations['multilingual']=new $class('multilingual',$this->i18nClassName(),$this->langForeignKey(),$options);
		return $this->with('multilingual');
	}

	protected function afterFind() {
		if($this->hasRelated('localized')) {
			$fields=$this->i18nAttributes();
			$related=$this->getRelated('localized');
			//if($row=current($related)) {
			foreach($fields as $field) {
				$this->$field=$related[$field];
			}
			//}
		} elseif($this->hasRelated('multilingual')) {
			$fields=$this->i18nAttributes();
			$related=$this->getRelated('multilingual');
			$i18nLanguages=$this->i18nLanguages();
			foreach($i18nLanguages as $lang) {
				foreach($fields as $field)
				$this->_i18nAttributes[$field.'_'.$lang]=isset($related[$lang][$field])?$related[$lang][$field]:null; // todo
			}
		}
		if($this->hasEventHandler('onAfterFind'))
		$this->onAfterFind(new CEvent($this));
	}

	protected function afterConstruct() {
		if($i18nLanguages=$this->i18nLanguages()) {
			$class=$this->i18nClassName();
			$obj=new $class;
			$fields=$this->i18nAttributes();
			foreach($i18nLanguages as $lang)
			foreach($fields as $field)
			$this->_i18nAttributes[$field.'_'.$lang]=$obj->$field;
		}
		if($this->hasEventHandler('onAfterConstruct'))
		$this->onAfterConstruct(new CEvent($this));
	}

	protected function afterSave() {
		if($this->_i18nAttributes) {
			$class=$this->i18nClassName();
			$model=call_user_func(array($class,'model'));
			$i18nLanguages=$this->i18nLanguages();
			$langField=$this->langField();
			$foreignKey=$this->langForeignKey();
			$fields=$this->i18nAttributes();
			foreach($i18nLanguages as $lang) {
				$obj=$model->find($foreignKey.'='.$this->primaryKey.' AND '.$langField.'=\''.$lang.'\'');
				if(!$obj) {
					$obj=new $class;
					$obj->$langField=$lang;
					$obj->$foreignKey=$this->primaryKey;
				}
				foreach($fields as $field) {
					$f=$field.'_'.$lang;
					$obj->$field=$this->$f;
				}

				$obj->save();
			}
			if($this->hasEventHandler('onAfterSave'))
			$this->onAfterSave(new CEvent($this));
		}
	}

	protected function afterDelete() {
		if($this->dropI18nDataAfterDelete && $this->i18nLanguages()) {
			$model=call_user_func(array($this->i18nClassName(),'model'));
			$foreignKey=$this->langForeignKey();
			foreach($model->findAllByAttributes(array($foreignKey=>$this->primaryKey)) as $i18n)
			$i18n->delete();
		}
		if($this->hasEventHandler('onAfterDelete'))
		$this->onAfterDelete(new CEvent($this));
	}


}
