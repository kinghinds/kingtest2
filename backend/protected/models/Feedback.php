<?php

class Feedback extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{feedback}}';
	}

	public function attributeLabels() {
		return array(
				'id' => '#编号',
				'content' => '详细内容',
				'name' => '咨询人姓名',
				'email' => '咨询人邮箱',
				'create_time' => '咨询日期',
				'sort_order' => '排序',
				'is_reply' => '回复状态',
		);
	}

	public function rules() {
		return array(
				array('content,name', 'safe'),
				array('email', 'email', 'allowEmpty' => true),
				array('sort_order', 'type', 'type' => 'integer'),
				array(
						'create_time',
						'type',
						'type' => 'date',
						'dateFormat' => 'yyyy-MM-dd'
				),
				array('is_reply', 'type', 'type' => 'boolean'),
		);
	}

	public function getPermalink() {
		if (empty($this->slug) == false && strlen(trim($this->slug)) > 0) {
			return Yii::app()
					->createUrl('feedback/view', array('slug' => $this->slug));
		} else {
			return Yii::app()
					->createUrl('feedback/view', array('id' => $this->primaryKey));
		}
	}

	public function getStatusText()
	{
		$id=$this->id;
		$answer = Answer::model()->find('feedback_id=:feedbackID', array(':feedbackID'=>$id));
		if ($this->is_reply == 0)
			return '<font color="blue">未回复</font>&nbsp;&nbsp;<a href="index.php?r=answer/reply&feedbackid='.$id.'">回复</a>';
		else if ($this->is_reply == 1)
			return '<font color="green">已回复</font>&nbsp;&nbsp;<a href="index.php?r=answer/view&id='.$answer->primaryKey.'">查看回复</a>';
	}

}

?>