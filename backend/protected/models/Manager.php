<?php

class Manager extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{manager}}';
	}

	public function attributeLabels() {
		return array(
				'manager_id' => '#编号', 
				'manager_role_id' => '管理员角色',
				'login_name' => '用户名',
				'login_password' => '用户密码', 
				'email' => 'E-mail',
				'login_times' => '登录次数',
				'create_manager_id' => '创建者',
				'create_time' => '创建时间',
				'last_login_time' => '最后登录时间', 
				'last_login_ip' => '最后登录IP地址',
				'is_allow_login' => '允许登录',
				'is_admin' => '是否为内置管理员'
		);
	}

	public function rules() {
		return array(
				array('manager_role_id', 'required'),
				array('manager_role_id', 'exist', 'className' => 'ManagerRole', 
						'attributeName' => 'manager_role_id'),
				array('login_name', 'required'),
				array('login_name', 'match', 'pattern' => '/^[a-z]+[a-z0-9-]*$/'),
				array('login_name', 'unique'),
				array('login_password', 'required', 'on' => 'insert'),
				array('login_password', 'length', 'min' => 6),
				array('email', 'email', 'allowEmpty' => true),
				array('is_allow_login', 'type', 'type' => 'boolean')
		);
	}

	public function relations() {
		return array(
				'managerRole' => array(self::BELONGS_TO, 'ManagerRole', 
						'manager_role_id')
		);
	}

	protected function beforeSave() {
		if ($this->isNewRecord) {
			$this->login_password = md5($this->login_password);
			$this->create_manager_id = Yii::app()->user->id;
			$this->create_time = new CDbExpression('NOW()');
		}
		return true;
	}
}

?>