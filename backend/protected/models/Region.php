<?php

class Region extends I18nActiveRecord {

	public function i18nAttributes() {
		return array(
				'title',
				'is_released'
		);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{region}}';
	}

	public function attributeLabels() {
		return array(
				'region_id' => '#编号', 
				'title' => '地区名称',
				'sort_order' => '排序',
				'is_released' => '发布'
		);
	}

	public function rules() {
		return array(
			array('title', 'required'),
			array('sort_order', 'type', 'type' => 'integer'),
			array('is_released', 'type', 'type' => 'boolean'),
			array('i18nFormData', 'type', 'type' => 'array')
		);
	}
	
	public static function getOptions() {
		static $level = 0;
		$items = array();
		$criteria = new CDbCriteria();
		$criteria->compare('is_released', 1);
		$models = self::model()->localized(null, false)->findAll($criteria);
		foreach ($models as $model) {
			$items[$model->primaryKey] = $model->title;
		}
		return $items;
	}

	public static function getSelects() {
		static $level = 0;
		$items = array();
		$criteria = new CDbCriteria();
		$criteria->compare('t.is_released', 1);
		$models = self::model()->localized(null, false)->findAll($criteria);
		foreach ($models as $model) {
			$criteria = new CDbCriteria();
			$criteria->compare('region_id', $model->primaryKey);
			$brands = Brand::model()->findAll($criteria);
			if(count($brands)>0){
				$items[$model->primaryKey] = $model->title;
			}
		}
		return $items;
	}
	
	public function getMinSortOrder() {
		return (int) Yii::app()->db->createCommand()
				->select('MIN(sort_order)')
				->from($this->tableName())
				->queryScalar();
	}

	public function getPreviousSortOrder() {
		return (int) Yii::app()->db->createCommand()
				->select('sort_order')
				->from($this->tableName())
				->where('sort_order < :sort_order')
				->order('sort_order DESC')
				->bindValue(':sort_order', $this->sort_order)
				->queryScalar();
	}

	public function getNextSortOrder() {
		return (int) Yii::app()->db->createCommand()
				->select('sort_order')
				->from($this->tableName())
				->order('sort_order ASC')
				->where('sort_order > :sort_order')
				->bindValue(':sort_order', $this->sort_order)
				->queryScalar();
	}

	public function getMaxSortOrder() {
		return (int) Yii::app()->db->createCommand()
				->select('MAX(sort_order)')
				->from($this->tableName())
				->queryScalar();
	}

	public function getLinkUrl() {
		if (preg_match('#^(http|https|ftp)://.+$#', $this->link_url)) {
			return $this->link_url;
		} else if (strpos($this->link_url, '/') === 0) {
			/*return Yii::app()->baseUrl . '/' . Yii::app()->language
					. $this->link_url;*/
			return Yii::app()->baseUrl . $this->link_url;
		} else {
			/*return Yii::app()->baseUrl . '/' . Yii::app()->language . '/'
					. $this->link_url;*/
			return Yii::app()->baseUrl . '/' . $this->link_url;
		}
	}
}

?>