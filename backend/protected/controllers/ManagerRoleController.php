<?php

class ManagerRoleController extends Controller {
	
	public function filters() {
		return array('accessControl');
	}

	public function accessRules() {
		return array(
				array('allow', 'users' => array('@')),
				array('deny', 'actions' => array('*'))
		);
	}

	public function actionIndex() {	
		if (Yii::app()->user->getIsSuperUser() == false 
				&& Yii::app()->user->checkAccess('viewManagerRole') == false) {
			throw new CHttpException(403);
		}

		$dataProvider = new CActiveDataProvider('ManagerRole', array(
				'sort' => array('defaultOrder' => 'manager_role_id ASC'),
				'pagination' => array('pageSize' => 20)
		));

		$this->breadcrumbs = array('管理员角色');

		$this->render('index', array(
				'dataProvider' => $dataProvider
		));
	}
	
	public function actionView($id) {
		if (Yii::app()->user->getIsSuperUser() == false 
				&& Yii::app()->user->checkAccess('viewManagerRole') == false) {
			throw new CHttpException(403);
		}

		if ($id <= 0) {
			throw new CHttpException(404);
		}
		
		$managerRole = ManagerRole::model()->findByPk($id);
		if (is_null($managerRole)) {
			throw new CHttpException(404);
		}

		$this->breadcrumbs = array(
				'管理员角色' => $this->createUrl('index'), 
				'查看'
		);
		
		$this->render('view', array(
				'managerRole' => $managerRole,
				'returnUrl' => $this->getReturnUrl()
		));
	}
	
	public function actionCreate() {
		if (Yii::app()->user->getIsSuperUser() == false 
				&& Yii::app()->user->checkAccess('createManagerRole') == false) {
			throw new CHttpException(403);
		}

		$managerRole = new ManagerRole();
		if (isset($_POST['ManagerRole'])) {
			$managerRole->attributes = Yii::app()->request->getPost('ManagerRole');
			$managerRole->privileges = implode(',', array_keys(ManagerRole::getAllPrivilege()));
			if ($managerRole->validate() && $managerRole->save()) {
				$this->setFlashMessage(strtr(
						'succ:<b>{name}</b> 已添加 {link}', array(
								'{name}' => $managerRole->name,
								'{link}' => CHtml::link('查看', array(
										'view', 'id' => $managerRole->manager_role_id
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		}

		$this->breadcrumbs = array(
				'管理员角色' => $this->createUrl('index'), 
				'添加'
		);
		
		$this->render('create', array(
				'managerRole' => $managerRole,
				'returnUrl' => $this->getReturnUrl()
		));
	}
	
	public function actionUpdate($id) {	
		if (Yii::app()->user->getIsSuperUser() == false 
				&& Yii::app()->user->checkAccess('updateManagerRole') == false) {
			throw new CHttpException(403);
		}

		if ($id <= 0) {
			throw new CHttpException(404);
		}
		
		$managerRole = ManagerRole::model()->findByPk($id);
		if (empty($managerRole)) {
			throw new CHttpException(404);
		}
		
		if (isset($_POST['ManagerRole'])) {
			$managerRole->setAttributes(Yii::app()->request->getPost('ManagerRole'));
			$managerRole->update_time = null;
			if ($managerRole->validate() && $managerRole->save()) {
				$this->setFlashMessage(strtr(
						'<b>{name}</b> 已更新 {link}', array(
								'{name}' => $managerRole->name,
								'{link}' => CHtml::link('查看', array(
										'view', 'id' => $managerRole->manager_role_id
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		}
		
		$this->breadcrumbs = array(
				'管理员角色' => $this->createUrl('index'), 
				'修改'
		);

		$this->render('create', array(
				'managerRole' => $managerRole,
				'returnUrl' => $this->getReturnUrl()
		));
	}
	
	public function actionDelete() {
		if (Yii::app()->user->getIsSuperUser() == false 
				&& Yii::app()->user->checkAccess('deleteManagerRole') == false) {
			throw new CHttpException(403);
		}

		$id = intval(Yii::app()->request->getQuery('id'));
		if ($id <= 0) {
			throw new CHttpException(404);
		}
		
		$managerRole = ManagerRole::model()->findByPk($id);
		if (empty($managerRole)) {
			throw new CHttpException(404);
		}
		
		if ($managerRole->is_admin) {
			throw new CHttpException(403, strtr(
					'管理员角色{name}为默认系统管理员组, 不允许被删除.', 
					array(
							'{name}' => $managerRole->name
					)
			));			
			/*$this->setFlashMessage(strtr(
					'管理员角色 <b>{name}</b> 为默认系统管理员组, 不允许被删除', 
					array(
							'{name}' => $managerRole->name
					)
			));
			$this->redirect(array('index'));*/
		} else if ($managerRole->managerCount > 0) {			
			throw new CHttpException(403, strtr(
					'管理员角色{name}存在{count}个关联用户, 不允许被删除. 如果你需要删除此管理员角色, 请先删除关联用户.', 
					array(
							'{name}' => $managerRole->name,
							'{count}' => $managerRole->managerCount
					)
			));
			/*$this->setFlashMessage(strtr(
					'管理员角色 <b>{name}</b> 存在关联用户({count}个), 不允许被删除', 
					array(
							'{name}' => $managerRole->name,
							'{count}' => $managerRole->managerCount
					)
			));
			$this->redirect(array('view', 'id' => $managerRole->manager_role_id));*/
		} else {
			if ($managerRole->delete()) {
				/*$this->setFlashMessage('message', '管理员角色 <b>' 
						. $managerRole->name . '</b> 已安全删除');*/
			} else {
				throw new CHttpException(403, '管理员角色' . $managerRole->name
						. '删除失败');
				/*$this->setFlashMessage('message', '管理员角色 <b>'
						. $managerRole->name . '</b> 删除失败');*/
			}			
			$this->redirect(array('index'));
		}
	}
	
	public function actionUpdatePrivilege($id) {
		if (Yii::app()->user->getIsSuperUser() == false 
				&& Yii::app()->user->checkAccess('updateManagerRolePrivilege') == false) {
			throw new CHttpException(403);
		}
		
		if ($id <= 0) {
			throw new CHttpException(404, '无效ID');
		}
		
		$managerRole = ManagerRole::model()->findByPk($id);
		if (empty($managerRole)) {
			throw new CHttpException(404, '没有找到 "' . $id . '" 管理员角色数据');
		}
		
		$form = new UpdateManagerRolePrivilegeForm();
		if (isset($_POST['UpdateManagerRolePrivilegeForm'])) {
			$form->setAttributes(Yii::app()->request->getPost(
					'UpdateManagerRolePrivilegeForm'));
			if ($form->validate()) {			
				$privileges = array();
				foreach ($form->privileges as $privilege) {
					if (empty($privilege) == false) {
						$privileges[] = $privilege;
					}
				}
				$managerRole->privileges = implode(',', $privileges);				
				if ($managerRole->save(true, array('privileges'))) {
					$this->setFlashMessage(strtr(
							'管理员角色<b>{name}</b> 权限已更新 {link}', array(
									'{name}' => $managerRole->name,
									'{link}' => CHtml::link('查看', array(
											'updatePrivilege',
											'id' => $managerRole->manager_role_id
									))
							)
					));
					$this->redirect($this->getReturnUrl());
				} else {
					$this->setFlashMessage('更新管理员角色数据失败');
				}
			}
		} else {
			$form->privileges = $managerRole->getPrivilegeArray();
		}
	
		$privileges = ManagerRole::$privileges;

		$this->breadcrumbs = array(
				'管理员角色' => $this->createUrl('index'), 
				'权限'
		);

		$this->render('updatePrivilege', array(
				'managerRole' => $managerRole,
				'form' => $form,
				'privileges' => $privileges,
				'returnUrl' => $this->getReturnUrl()
		));
	}
}
?>

