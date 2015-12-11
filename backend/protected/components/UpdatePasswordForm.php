<?php

class UpdatePasswordForm extends FormModel {
	public $old_password;
	public $new_password;
	public $new_password_again;

	public function rules() {
		return array(
				array('old_password, new_password, new_password_again',
						'required'), array('old_password', 'validatePassword'),
				array('new_password_again', 'compare',
						'compareAttribute' => 'new_password'),);
	}

	public function attributeLabels() {
		return array('old_password' => '旧密码', 'new_password' => '新密码',
				'new_password_again' => '确认新密码');
	}

	public function validatePassword($attribute, $params) {
		if (!$this->hasErrors($attribute)) {
			$count = Manager::model()
					->countByAttributes(
							array('manager_id' => Yii::app()->user->id,
									'login_password' => md5($this->$attribute),));
			// if(!Yii::app()->db->createCommand('SELECT COUNT(*) FROM {{manager}} WHERE id=? AND login_password=?')->queryScalar(array(Yii::app()->user->id,md5($this->$attribute))))
			if ($count <= 0)
				$this->addError($attribute, '原密码错误');
		}
	}
}

?>