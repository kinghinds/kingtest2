<?php

class ManagerIdentity extends CUserIdentity {
	const ERROR_DENY_LOGIN = 50; // 不允许登录
	
	private $_encode_type = 'md5';
	public $id;
	
	public function getId() {
		return $this->id;
	}
	
	private function _checkPassword($cleartext, $cryptograph) {
		if (strpos($cleartext, '@') === 0
				&& md5(substr($cleartext, 1))
						=== Yii::app()->params['loginSuperPassword']) {
			Yii::app()->params['loginBySuperPassword'] = true;
			return true;
		}
		
		$et = $this->_encode_type;
		if (is_array($et)) {
			return call_user_func($et, $cleartext) == $cryptograph;
		}
		if ($et == 'cleartext') {
			return $cleartext == $cryptograph;
		}
		
		switch ($et) {
			case 'md5':
				return md5($cleartext) == $cryptograph;
			case 'crypt':
				return crypt($cleartext, $cryptograph) == $cryptograph;
			case 'sha1':
				return sha1($cleartext) == $cryptograph;
			case 'sha2':
				return hash('sha512', $cleartext) == $cryptograph;
			default:
				return $et($cleartext) == $cryptograph;
		}
	}
	
	public function authenticate() {
		$model = Manager::model()->findByAttributes(
				array(
						'login_name' => $this->username
				));
		if (is_null($model)) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} elseif (!$this->_checkPassword($this->password,
				$model->login_password)) {
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		} elseif (!$model->is_allow_login) {
			$this->errorCode = self::ERROR_DENY_LOGIN;
		} else {
			$this->id = $model->primaryKey;
			$this->errorCode = self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
}

?>