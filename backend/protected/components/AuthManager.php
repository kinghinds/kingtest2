<?php

class AuthManager extends CPhpAuthManager {

	private $_items = array();
	private $_children = array();
	private $_assignments = array();

	public function init() {
		parent::init();
		$this->load();
	}

	public function checkAccess($itemName, $userId, $params = array()) {
		if (!isset($this->_items[$itemName])) {
			return false;
		}
		$item = $this->_items[$itemName];		
		Yii::trace('Checking permission "' . $item->getName() . '"', 'system.web.auth.CPhpAuthManager');
		if ($this->executeBizRule($item->getBizRule(), $params, $item->getData())) {
			if (in_array($itemName, $this->defaultRoles)) {
				return true;
			}
			if (isset($this->_assignments[$userId][$itemName])) {
				$assignment = $this->_assignments[$userId][$itemName];
				if ($this->executeBizRule($assignment->getBizRule(),$params,$assignment->getData())) {
					return true;
				}
			}
			foreach ($this->_children as $parentName => $children) {
				if (isset($children[$itemName]) && $this->checkAccess($parentName, $userId, $params)) {
					return true;
				}
			}
		}
		return false;
	}

	public function load() {
		$this->clearAll();

		$defaultPrivileges = ManagerRole::$privileges;
		$userPrivileges = explode(",", Yii::app()->user->getState('privileges'));
		$items = array();
		foreach ($defaultPrivileges as $privilege) {
			foreach ($privilege['items'] as $item) {
				$items[$item['privilege']] = array(
					'name' => $privilege['label'] . '_' . $item['label'],
					'type' => 0,
					'description' => '',
					'bizRule' => '',
					'data' => array(),
					'assignments' => array(
						Yii::app()->user->id => array(
							'bizRule' => '',
							'data' => ''
						)
					)
				);

				if (in_array($item['privilege'], $userPrivileges) == false) {
					unset($items[$item['privilege']]['assignments']);
				}
			}
		}

		foreach ($items as $name => $item) {
			$this->_items[$name] = new CAuthItem($this, $name, $item['type'], 
					$item['description'], $item['bizRule'], $item['data']);
		}

		foreach ($items as $name => $item) {
			if (isset($item['children'])) {
				foreach($item['children'] as $childName) {
					if (isset($this->_items[$childName])) {
						$this->_children[$name][$childName] = $this->_items[$childName];
					}
				}
			}			
			if (isset($item['assignments'])) {
				foreach ($item['assignments'] as $userId => $assignment) {
					$this->_assignments[$userId][$name] = new CAuthAssignment(
						$this, $name, $userId, $assignment['bizRule'], 
						$assignment['data']);
				}
			}
		}
	}
}

?>