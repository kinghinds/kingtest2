<?php

class FeedbackForm extends CFormModel {
	
	public $name;
	public $content;
	public $email;
	public $phone;
	public function attributeLabels() {
		return array(
				'name' => Yii::t('common','您的名字'),
				'content' => Yii::t('common','您的留言'),
				'phone' =>Yii::t('common','您的手机'),
				'email' =>Yii::t('common','您的Email'),
		);
	}
	public function rules() {
		return array(
				array('name, content, phone,email', 'required'),
				array('phone', 'match','pattern'=>'/^(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/','message'=>Yii::t('common','请正确填写号码！')),
				array('email', 'email'),
		);
	}

	public function submit() {
		if ($this->validate()) {
			$feedback = new Feedback();
			$feedback->name = $this->name;
			$feedback->content = $this->content;
			$feedback->email = $this->email;
			$feedback->phone = $this->phone;
			$feedback->create_time = date('Y-m-d');
			$feedback->is_reply = 0;
			return $feedback->save();
			
		}
		return false;
	}
}

?>