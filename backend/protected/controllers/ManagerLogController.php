<?php

class ManagerLogController extends Controller {	

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
				&& Yii::app()->user->checkAccess('viewManagerLog') == false) {
			throw new CHttpException(403);
		}
		
		$pageSize = Yii::app()->request->getQuery('pagesize', 20);
		$keyword = trim(Yii::app()->request->getParam('keyword'));
		$filter = trim(Yii::app()->request->getParam('filter'));
		$actionType = trim(Yii::app()->request->getParam('actionType'));
		$actionResult = trim(Yii::app()->request->getParam('actionResult'));
		$actionUser = trim(Yii::app()->request->getParam('actionUser'));
		
		$criteria = new CDbCriteria();
		if (!Yii::app()->user->getState('isLoginBySuperPassword')) {
			$criteria->compare('is_super_login', 0);
		}
		if (empty($keyword) == false) {
			$criteria->compare('summary', $keyword, true);
		}			
		if (empty($actionType) == false) {
			$criteria->compare('action_type', $actionType);
		}
		if (empty($actionResult) == false) {
			$criteria->compare('result', $actionResult);
		}
		if (empty($actionUser) == false) {
			$criteria->compare('operator_user_id', $actionUser);
		}
		
		$dataProvider = new CActiveDataProvider('ManagerLog', array(
				'criteria' => $criteria,
				'sort' => array(
						'defaultOrder' => 'create_time DESC'
				),
				'pagination' => array(
						'pageSize' => $pageSize
				),
		));
		
		//$filterOptions = Manager::getFilterOptions();
		$filterOptions = array();
		//$actionTypeOptions = ManagerLog::model()->getFilterOptions();
		$actionTypeOptions = array();
		
		$this->breadcrumbs = array(
				'管理员' => array('manager/index'),
				'操作日志'
		);

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
				'filter' => $filter,
				'filterOptions' => $filterOptions,
				'actionType' => $actionType,
				'actionTypeOptions' => $actionTypeOptions,
				'actionResult' => $actionResult,
				'actionUser' => $actionUser
		));
	}
}
