<?php

class ManagerController extends Controller {

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
		ManagerLog::logCurrentUserAction(1, '删除管理员', 
				'test');

		if (Yii::app()->user->getIsSuperUser() == false 
			&& Yii::app()->user->checkAccess('viewManager') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getParam('keyword'));
		$managerRoleId = intval(Yii::app()->request->getParam('manager_role_id'));
		$filter = trim(Yii::app()->request->getParam('filter'));

		$criteria = new CDbCriteria();
		if ($managerRoleId > 0) {
			$criteria->compare('manager_role_id', managerRoleId);
		}
		if (empty($filter) == false) {
			switch ($filter) {
				case 'loginDisabled':
					$criteria->compare('is_allow_login', 0);
					break;
				case 'loginToday':
					$criteria->compare('last_login_time', 
							'>=' . strtotime('today'));
					break;
				case 'notLogin3days':
					$criteria->compare('last_login_time', 
							'>=' . strtotime('-3 day'));
					break;
				case 'notLogin1week':
					$criteria->compare('last_login_time', 
							'>=' . strtotime('-1 week'));
					break;
			}
		}
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('manager_id', substr($keyword, 1));
			} else {
				$criteria->compare('login_name', $keyword, true);
			}
		}
		
		$dataProvider = new CActiveDataProvider('Manager', array(
				'criteria' => $criteria,
				'sort' => array('defaultOrder' => 'login_name ASC'),
				'pagination' => array('pageSize' => $pageSize)
		));

		$managerRoleOptions = ManagerRole::model()->getOptions();

		$filterOptions = array(
				'loginDisabled' => '禁止登录的用户', 
				'loginToday' => '今天登录过的用户',
				'notLogin3days' => '超过3天没有登录的用户',
				'notLogin1week' => '超过1个星期登录的用户'
		);

		$this->breadcrumbs = array(
			'管理员'
		);

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
				'managerRoleId' => $managerRoleId,
				'managerRoleOptions' => $managerRoleOptions,
				'filter' => $filter,
				'filterOptions' => $filterOptions
		));
	}

	public function actionView($id) {
		if (Yii::app()->user->getIsSuperUser() == false 
			&& Yii::app()->user->checkAccess('viewManager') == false) {
			throw new CHttpException(403);
		}

		$manager = Manager::model()->findByPk($id);
		if (is_null($manager)) {
			throw new CHttpException(404);
		}
		
		$this->breadcrumbs = array(
				'管理员' => array('index'), 
				'查看'
		);

		$this->render('view', array(
				'manager' => $manager,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionCreate() {
		if (Yii::app()->user->getIsSuperUser() == false 
				&& Yii::app()->user->checkAccess('createManager') == false) {
			throw new CHttpException(403);
		}

		$manager = new Manager();
		if (isset($_POST['Manager'])) {
			$manager->attributes = Yii::app()->request->getPost('Manager');
			if ($manager->validate() && $manager->save()) {
				$this->setFlashMessage('管理员添加成功');
				ManagerLog::logCurrentUserAction(1, '添加管理员', $manager->login_name);
				$this->redirect($this->getReturnUrl());
			}
		}

		$managerRoleOptions = ManagerRole::model()->getOptions();

		$this->breadcrumbs = array(
				'管理员' => array('index'), 
				'添加'
		);

		$this->render('create', array(
				'manager' => $manager,
				'managerRoleOptions' => $managerRoleOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdate($id) {
		if (Yii::app()->user->getIsSuperUser() == false 
			&& Yii::app()->user->checkAccess('updateManager') == false) {
			throw new CHttpException(403);
		}

		$manager = Manager::model()->findByPk($id);
		if (is_null($manager)) {
			throw new CHttpException(404);
		}
		$isAdmin = $manager->is_admin;

		if (isset($_POST['Manager'])) {
			$manager->attributes = Yii::app()->request->getPost('Manager');
			if ($isAdmin == true) {
				unset($manager->manager_role_id);
			}
			if ($manager->validate() && $manager->validate()) {
				if ($manager->login_password) {
					$manager->login_password = md5($manager->login_password);
				} else {
					unset($manager->login_password);
				}
				if ($manager->save()) {
					$this->setFlashMessage(strtr(
							'<strong>{link}</strong> 管理员修改成功', array(
									'{link}' => CHtml::link(
											$manager->login_name,
											array('view', 'id' => $manager->primaryKey)
									)
							)
					));
					ManagerLog::logCurrentUserAction(1, '修改管理员',
							$manager->login_name);
					$this->redirect($this->getReturnUrl());
				} else {
					$this->setFlashMessage('管理员修改失败', 'err');
				}
			}
		} else {
			$manager->login_password = '';
		}

		$managerRoleOptions = ManagerRole::model()->getOptions();

		$this->breadcrumbs = array(
				'管理员' => array('index'), 
				'修改'
		);

		$this->render('create', array(
				'manager' => $manager,
				'managerRoleOptions' => $managerRoleOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionDelete() {
		if (Yii::app()->user->getIsSuperUser() == false 
			&& Yii::app()->user->checkAccess('deleteManager') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$manager = Manager::model()->findByPk($id);
		if (is_null($manager)) {
			throw new CHttpException(403);
		}

		if ($manager->is_admin) {
			throw new CHttpException(403, strtr(
					'管理员{name}为默认系统管理员, 不允许被删除.', array(
							'{name}' => $manager->login_name
					)
			));
		}

		$flag = $manager->delete();
		ManagerLog::logCurrentUserAction($flag, '删除管理员', 
				$manager->login_name);
	}
}
?>