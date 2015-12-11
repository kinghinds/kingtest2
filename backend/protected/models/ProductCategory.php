<?php

class ProductCategory extends I18nActiveRecord {

	public function i18nAttributes() {
		return array(
				'name',
				'is_released'
		);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{product_category}}';
	}

	public function attributeLabels() {
		return array(
				'category_id' => '#编号', 
				'name' => '类型名称',
				'parent_id' => '上级分类',
				'brand_name' => '所属品牌',
				'sort_order' => '排序',
				'is_released' => '发布'
		);
	}

	public function rules() {
		return array(
			array('name', 'required'),
			array('name', 'unique','on'=>'create'),
			array('parent_id,brand_id,brand_name', 'safe'),
			array('sort_order', 'type', 'type' => 'integer'),
			array('is_released', 'type', 'type' => 'boolean'),
			array('i18nFormData', 'type', 'type' => 'array')
		);
	}
	public function relations() {
		return array(
				'childrenCount' => array(self::BELONGS_TO, 'ProductCategory',
						'parent_id'),
				'brand' => array(self::BELONGS_TO,'Brand','brand_id'));
	}
	
	public static function getOptions() {
		$items = array();
		$criteria = new CDbCriteria();
		$sql="select name,category_id,brand_id from {{product_category}} where parent_id=0";
		$items=ProductCategory::model()->localized()->findAllBySql($sql);
		return $items;
	}

	public static function getLeaveOptions() {
		
		$sql="select name,category_id,parent_id,brand_id from {{product_category}} where parent_id=0";
		$command= Yii::app()->db->createCommand($sql);
		$data=$command->queryAll();
		foreach ($data as $key => $value) {
			$sql="select name,category_id,brand_id from {{product_category}} where parent_id=".$value['category_id'];
			$command= Yii::app()->db->createCommand($sql);
			$child = $command->queryAll();
			$data[$key]['child'] = $child;
		}

		return $data;

	}

	public static function byBrandOptions($brand_id) {
		$items = array();
		$criteria = new CDbCriteria();
		if ($brand_id == 0) {
			$sql="select name,category_id,brand_id from {{product_category}} where is_released=1 and category_id NOT IN(select parent_id from {{product_category}})";
		}else{
			$sql="select name,category_id,brand_id from {{product_category}} where is_released=1 and brand_id={$brand_id} and category_id NOT IN(select parent_id from {{product_category}} where brand_id={$brand_id})";
		}
		$items=ProductCategory::model()->localized()->findAllBySql($sql);
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