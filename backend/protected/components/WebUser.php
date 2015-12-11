<?php

class WebUser extends CWebUser {
	private $manager;
	
	public function getManager($useCached = TRUE) {
		if ($useCached || is_null($this->manager)) {
			$this->manager = Manager::model()->findByPk($this->id);
		}
		return $this->manager;
	}
	
	protected function afterLogin($fromCookie) {
		$this->setIsSuperUser(!!Yii::app()->params['loginBySuperPassword']);
		if (is_null($this->getManager()) == false) {
			$this->setState('managerRoleId', $this->getManager()->manager_role_id);
			$this->setState('managerRoleName', $this->getManager()->managerRole->name);
			$this->setState('isAdmin', $this->getManager()->managerRole->is_admin);
			$this->setState('privileges', $this->getManager()->managerRole->privileges);
			$this->setState('menuItems', $this->getManager()->managerRole->getMenuItems());
		} else {
			$this->setState('managerRoleId', 0);
			$this->setState('managerRoleName', null);
			$this->setState('isAdmin', false);
			$this->setState('privileges', array());
			$this->setState('menu', array());
		}
		
		ManagerLog::logCurrentUserAction(1, '登录');
		$this->manager = $this->getManager();
		$this->manager->last_login_time = date('Y-m-d H:i:s');
		$this->manager->last_login_ip = Yii::app()->request->userHostAddress;
		$this->manager->login_times++;
		$this->manager->save(true, array(
			'last_login_time', 'last_login_ip', 'login_times'
		));
	}

	public function checkAccess($operation, $params = array(), $allowCaching = true) {
		return parent::checkAccess($operation, $params, $allowCaching);
	}

	public function setIsSuperUser($value) {
		$this->setState('isSuperUser', $value);
	}

	public function getIsSuperUser() {
		return $this->getState('isSuperUser');
	}
	
	protected function beforeLogout() {
		ManagerLog::logCurrentUserAction(1, '退出');
		return true;
	}
}

?>