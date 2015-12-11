<?php

class UpdateMemberPasswordForm extends CFormModel {
	public $member_id;
	public $new_password;
	public $confirm_password;

	public function rules() {
		return array(
				array('member_id, new_password, confirm_password', 'required'),
				array('confirm_password', 'compare',
						'compareAttribute' => 'new_password'));
	}

	public function attributeLabels() {
		return array('member_id' => '会员', 'new_password' => '新密码',
				'confirm_password' => '确认新密码');
	}

	public function submit() {
		if ($this->hasErrors()) {
			return false;
		} else {
			$member = Member::model()->findByPk($this->member_id);
			if (empty($member)) {
				$this->addError('member_id', '会员不存在');
				return false;
			}

			$member->password = md5($this->new_password);
			return $member->save(true, array('password'));
		}
	}
}

?>