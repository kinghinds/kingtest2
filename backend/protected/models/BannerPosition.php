<?php

class BannerPosition extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{banner_position}}';
	}

	public function attributeLabels() {
		return array('banner_position_id' => '#编号', 'title' => '标题',
				'width' => '图片宽度', 'height' => '图片高度');
	}

	public function rules() {
		return array(array('title', 'required'), array('title', 'safe'),
				array('width, height', 'type', 'type' => 'integer'));
	}

	public function getOptions() {
		$criteria = new CDbCriteria();
		//$criteria->order = 'title ASC';
		$bannerPositions = self::model()->findAll($criteria);
		return CHtml::listData($bannerPositions, 'banner_position_id', 'title');
	}
}

?>