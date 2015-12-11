<?php

class LoginForm extends CFormModel {
	private $_identity;
	public $loginName;
	public $loginPassword;
	public $validationCode;
	public $rememberMe;

	public function attributeLabels() {
		return array(
			'loginName' => '用户名', 
			'loginPassword' => '密码',
			'validationCode' => '验证码'
		);
	}

	public function rules() {
		return array(
			array('loginName, loginPassword', 'required'),
			array('loginPassword', 'authenticate'),
			array('validationCode', 'captcha', 
					'allowEmpty' => (YII_DEBUG || !extension_loaded('gd'))),
			array('rememberMe', 'boolean')
		);
	}

	public function authenticate($attribute, $params) {
		if ($this->hasErrors() == false) {
			$this->_identity = new ManagerIdentity($this->loginName, 
					$this->loginPassword);
			if ($this->_identity->authenticate() == false) {
				if ($this->_identity->errorCode 
						== ManagerIdentity::ERROR_DENY_LOGIN) {
					$this->addError('loginPassword', '帐号不允许登录');
				} else {
					$this->addError('loginPassword', '密码或账户错误');
				}
			}
		}
	}

	public function login() {
		if ($this->_identity === null) {
			$this->_identity = new ManagerIdentity($this->login_name,
					$this->loginPassword);
			$this->_identity->authenticate();
		}
		if ($this->_identity->errorCode === ManagerIdentity::ERROR_NONE) {
			$duration = $this->rememberMe ? 86400 * 30 : 0; // 30 days
			Yii::app()->user->login($this->_identity, $duration);
			
			return true;
		} else {
			return false;
		}
	}
}

?>