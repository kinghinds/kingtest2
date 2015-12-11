<?php

class NewsController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewNews') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.case_id";
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('case_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.title', $keyword, true, 'OR');
			}
		}

		$dataProvider = new CActiveDataProvider(
				News::model()->multilingual()->together(), array(
						'criteria' => $criteria,
						'sort' => array(
								'defaultOrder' => 't.release_date DESC, t.case_id DESC'
						),
						'pagination' => array('pageSize' => $pageSize)
				)
		);
		$this->breadcrumbs = array('成功案例');

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
		));
	}

	public function actionView($id) {
		if (Yii::app()->user->checkAccess('viewNews') == false) {
			throw new CHttpException(403);
		}

		$cases = News::model()->multilingual()->findByPk($id);
		if (is_null($cases)) {
			throw new CHttpException(404);
		}

		$this->breadcrumbs = array(
				'成功案例' => array('index'), 
				'查看'
		);

		$this->render('view', array(
				'cases' => $cases,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionCreate() {
		if (Yii::app()->user->checkAccess('createNews') == false) {
			throw new CHttpException(403);
		}

		$cases = new News();
		if (isset($_POST['News'])) {
			$cases->attributes = Yii::app()->request->getPost('News');

			if ($cases->validate() && $cases->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 成功案例添加成功', array(
								'{link}' => CHtml::link($cases->title, array(
									'view', 'id' => $cases->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		} else {
			$cases->release_date = date('Y-m-d');
			$cases->is_released = 1;
		}

		$this->breadcrumbs = array(
				'成功案例' => array('index'), 
				'添加'
		);

		$this->render('create', array(
				'cases' => $cases, 
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdate($id) {
		if (Yii::app()->user->checkAccess('updateNews') == false) {
			throw new CHttpException(403);
		}

		$cases = News::model()->multilingual()->findByPk($id);
		if (is_null($cases)) {
			throw new CHttpException(404);
		}

		if (isset($_POST['News'])) {
			$cases->attributes = Yii::app()->request->getPost('News');
		
			if ($cases->validate() && $cases->save()) {

				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 成功案例修改成功', array(
								'{link}' => CHtml::link($cases->title, array(
										'view', 'id' => $cases->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		} 

		$this->breadcrumbs = array(
				'成功案例' => array('index'), 
				'修改'
		);

		$this->render('create', array(
				'cases' => $cases, 
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdateIsReleased() {
		if (Yii::app()->user->checkAccess('updateNews') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getPost('id');
		$language = Yii::app()->request->getPost('language');
		$isReleased = Yii::app()->request->getPost('is_released');
		if ($language == I18nHelper::getFrontendSourceLanguage()) {
			echo News::model()->updateByPk($id, array(
					'is_released' => $isReleased
			));
		} else {
			echo NewsI18n::model()->updateAll(
					array('is_released' => $isReleased),
					'owner_id = :owner_id AND lang = :lang',
					array(':owner_id' => $id, ':lang' => $language)
			);
		}
	}

	public function actionDelete() {
		if (Yii::app()->user->checkAccess('deleteNews') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$cases = News::model()->multilingual()->findByPk($id);
			if (is_null($cases) == false) {
				$cases->delete();
			}
		}
	}

	public function actionBatchDelete() {
		if (Yii::app()->user->checkAccess('deleteNews') == false) {
			throw new CHttpException(403);
		}
		
		$idList = Yii::app()->request->getPost('case_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('case_id', $idList);
			$casess = News::model()->findAll($criteria);
			$flag = 0;
			foreach ($casess as $cases) {
				if ($cases->delete()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('成功案例已成功删除');
			} else {
				$this->setFlashMessage('成功案例删除失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

}

?>