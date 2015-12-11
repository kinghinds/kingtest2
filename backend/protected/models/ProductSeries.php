<?php

class ProductSeries extends I18nActiveRecord {

	public function i18nAttributes() {
		return array(
				'title',
				'norms',
				'series_model',
				'content',
				'is_released'
		);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{product_series}}';
	}

	public function attributeLabels() {
		return array(
				'series_id' => '#编号', 
				'title' => '系列标题',
				'norms' => '规格',
				'series_model' => '型号',
				'content' => '详细内容',
				'category_id' => '所属类别',
				'brand_id'  => '所属品牌',
				'sort_order' => '排序',
				'is_released' => '发布'
		);
	}

	public function rules() {
		return array(
			array('title', 'required'),
			array('title', 'checkTitle','on'=>'create'),
			array('category_id,content,norms,series_model,brand_id', 'safe'),
			array('sort_order', 'type', 'type' => 'integer'),
			array('is_released', 'type', 'type' => 'boolean'),
			array('i18nFormData', 'type', 'type' => 'array')
		);
	}
	public function relations() {
		return array(
				'category' => array(self::BELONGS_TO, 'ProductCategory',
						'category_id'),
				);
	}
	public function checkTitle($attribute,$params){  
	    $olddata = self::model()->findByAttributes(array('title'=>$this->title,'category_id'=>$this->category_id));  
	    if(!empty($olddata)){  
	        $this->addError($attribute, '相同的系列已经存在!');  
	    }  
	}
	
	public static function getOptions() {
		$items = array();
		$criteria = new CDbCriteria();
		$criteria->compare('is_released', 1);
		$models = self::model()->localized(null, false)->findAll($criteria);
		foreach ($models as $model) {
			$items[$model->primaryKey] = $model->title;
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