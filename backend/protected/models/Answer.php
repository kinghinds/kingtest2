<?php

class Answer extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{answer}}';
	}

	public function attributeLabels() {
		return array(
				'answer_id' => '#编号',
				'feedback_id' => '咨询问题标题',
				'content' => '回复内容',
				'reply_time' => '回复日期',
		);
	}

	public function rules() {
		return array(
				array('title, content,feedback_id', 'safe'),
				array(
						'reply_time',
						'type',
						'type' => 'date',
						'dateFormat' => 'yyyy-MM-dd'
				),
		);
	}

	public function relations() {
		return array(
				'feedback' => array(
						self::BELONGS_TO,
						'Feedback',
						'feedback_id'
				)
		);
	}

	public function getPermalink() {
		if (empty($this->slug) == false && strlen(trim($this->slug)) > 0) {
			return Yii::app()
					->createUrl('answer/view', array('slug' => $this->slug));
		} else {
			return Yii::app()
					->createUrl('answer/view', array('id' => $this->primaryKey));
		}
	}
}

?>