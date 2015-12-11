<?php

class UpdateManagerRolePrivilegeForm extends CFormModel {
	
	public $privileges = array();
	
	public function rules() {
		return array(
				array(
						'privileges',
						'type',
						'type' => 'array'
				),
				array(
						'privileges',
						'validatePrivilege'
				)
		);
	}
	
	public function attributeLabels() {
		return array(
				'privileges' => '权限'
		);
	}
	
	public function validatePrivilege() {
		if ($this->hasErrors('privileges') == false) {
			$allPrivileges = array_keys(ManagerRole::getAllPrivilege());
			foreach ($this->privileges as $privilege) {
				if (empty($privilege) == false) {
					if (in_array($privilege, $allPrivileges) == false) {
						$this->addError('privileges', '权限无法识别');
						break;
					}
				}
			}
		}
	}
}

?>