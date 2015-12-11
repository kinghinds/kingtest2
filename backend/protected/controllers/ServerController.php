<?php

class ServerController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewServer') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.server_id";
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('server_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.name', $keyword, true, 'OR');
			}
		}

		$dataProvider = new CActiveDataProvider(
				Server::model()->multilingual()->together(), array(
						'criteria' => $criteria,
						'sort' => array(
								'defaultOrder' => 't.sort_order ASC'
						),
						'pagination' => array('pageSize' => $pageSize)
				)
		);
		$this->breadcrumbs = array('服务');

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
		));
	}

	public function actionCreate() {
		if (Yii::app()->user->checkAccess('createServer') == false) {
			throw new CHttpException(403);
		}

		$server = new Server();

		if (isset($_POST['Server'])) {
			$server->attributes = Yii::app()->request->getPost('Server');
			$server->serverFile = CUploadedFile::getInstance($server, 
					'serverFile');			
			foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) {
				$server->i18nFormData['serverFile_' . $lang] = 
						CUploadedFile::getInstance($server, 
								'i18nFormData[serverFile_' . $lang . ']');
			}
			$server->sort_order = $server->getMaxSortOrder() + 1;
			if ($server->validate() && $server->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 服务添加成功', array(
								'{link}' => CHtml::link($server->name, array(
									'view', 'id' => $server->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		}

		$this->breadcrumbs = array(
				'服务' => array('index'), 
				'添加'
		);
		$this->render('create', array(
				'server' => $server,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdate($id) {
		if (Yii::app()->user->checkAccess('updateServer') == false) {
			throw new CHttpException(403);
		}

		$server = Server::model()->multilingual()->findByPk($id);
		if (is_null($server)) {
			throw new CHttpException(404);
		}

		if (isset($_POST['Server'])) {
			$server->attributes = Yii::app()->request->getPost('Server');
			$server->serverFile = CUploadedFile::getInstance($server, 
					'serverFile');			
			foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) {
				$server->i18nFormData['serverFile_' . $lang] = 
						CUploadedFile::getInstance($server, 
								'i18nFormData[serverFile_' . $lang . ']');
			}
			if ($server->validate() && $server->save()) {

				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 服务修改成功', array(
								'{link}' => CHtml::link($server->name, array(
										'view', 'id' => $server->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		} 

		$this->breadcrumbs = array(
				'服务' => array('index'), 
				'修改'
		);
		$this->render('create', array(
				'server' => $server,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdateIsReleased() {
		if (Yii::app()->user->checkAccess('updateServer') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getPost('id');
		$language = Yii::app()->request->getPost('language');
		$isReleased = Yii::app()->request->getPost('is_released');
		if ($language == I18nHelper::getFrontendSourceLanguage()) {
			echo Server::model()->updateByPk($id, array(
					'is_released' => $isReleased
			));
		} else {
			echo ServerI18n::model()->updateAll(
					array('is_released' => $isReleased),
					'owner_id = :owner_id AND lang = :lang',
					array(':owner_id' => $id, ':lang' => $language)
			);
		}
	}

	public function actionDelete() {
		if (Yii::app()->user->checkAccess('deleteServer') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$cases = Server::model()->multilingual()->findByPk($id);
			if (is_null($cases) == false) {
				$cases->delete();
			}
		}
	}

	public function actionBatchDelete() {
		if (Yii::app()->user->checkAccess('deleteServer') == false) {
			throw new CHttpException(403);
		}
		$idList = Yii::app()->request->getPost('server_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('server_id', $idList);
			$models = Server::model()->findAll($criteria);
			$flag = 0;
			foreach ($models as $model) {
				if ($model->delete()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('服务已成功删除');
			} else {
				$this->setFlashMessage('服务删除失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}


	public function actionSortFirst() {
		if (Yii::app()->user->checkAccess('updateServer') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$banner = Server::model()->findByPk($id);
		if (empty($banner) == false) {
			$sortOrder = $banner->getMinSortOrder();
			if ($sortOrder < 2) {
				$banner->updateAll(array(
					'sort_order' => new CDbExpression('sort_order + 1')
				));
			}
			$banner->updateByPk($banner->primaryKey, array(
				'sort_order' => ($sortOrder - 1)
			));
		}
		$this->redirect(array('index'));
	}

	public function actionSortPrevious() {
		if (Yii::app()->user->checkAccess('updateServer') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$banner = Server::model()->findByPk($id);
		if (empty($banner) == false) {
			$sortOrder = $banner->getPreviousSortOrder();
			if ($sortOrder > 0) {
				$banner->updateAll(
						array('sort_order' => $banner->sort_order),
						'sort_order = :sort_order',
						array('sort_order' => $sortOrder)
				);
				$banner->updateByPk($banner->primaryKey, array(
						'sort_order' => $sortOrder
				));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortNext() {
		if (Yii::app()->user->checkAccess('updateServer') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$banner = Server::model()->findByPk($id);
		if (empty($banner) == false) {
			$sortOrder = $banner->getNextSortOrder();
			if ($sortOrder > 0) {
				$banner->updateAll(
						array('sort_order' => $banner->sort_order),
						'sort_order = :sort_order',
						array('sort_order' => $sortOrder)
				);
				$banner->updateByPk($banner->primaryKey, array(
						'sort_order' => $sortOrder
				));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortLast() {
		if (Yii::app()->user->checkAccess('updateServer') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$banner = Server::model()->findByPk($id);
		if (empty($banner) == false) {
			$banner->updateByPk($banner->primaryKey, array(
					'sort_order' => ($banner->getMaxSortOrder() + 1)
			));
		}
		$this->redirect(array('index'));
	}

	public function actionSortSpecify() {
		if (Yii::app()->user->checkAccess('updateServer') == false) {
			throw new CHttpException(403);
		}
		
		if (isset($_POST['target_id']) && isset($_POST['pos'])) {
			$id = Yii::app()->request->getQuery('id');
			$targetId = Yii::app()->request->getPost('target_id');
			$pos = Yii::app()->request->getPost('pos', '1');

			$baseModel = Server::model()->findByPk($id);
			if (empty($baseModel)) {
				echo CJSON::encode(array(
						'result' => false, 
						'message' => '操作对象不存在'
				));
				Yii::app()->end();
			}

			$targetModel = Server::model()->findByPk($targetId);
			if (empty($targetModel)) {
				echo CJSON::encode(array(
						'result' => false, 
						'message' => '编号不存在'
				));
				Yii::app()->end();
			}

			if ($pos == '1') {
				$sortOrder = $targetModel->getPreviousSortOrder();
				if ($sortOrder > 0) {
					$baseModel->updateAll(
							array('sort_order' => new CDbExpression('sort_order + 1')),
							'sort_order > :sort_order',
							array(':sort_order' => $sortOrder)
					);
					$baseModel->updateByPk($baseModel->primaryKey, array(
							'sort_order' => $targetModel->sort_order
					));
				} else {
					$baseModel->updateAll(array(
							'sort_order' => new CDbExpression('sort_order + 1')
					));
					$baseModel->updateByPk($baseModel->primaryKey, array(
							'sort_order' => ($baseModel->getMinSortOrder() - 1)
					));
				}
			} else {
				$sortOrder = $targetModel->getNextSortOrder();
				if ($sortOrder > 0) {
					$targetModel->updateAll(
							array('sort_order' => new CDbExpression('sort_order + 1')),
							'sort_order > :sort_order',
							array(':sort_order' => $targetModel->sort_order)
					);
					$baseModel->updateByPk($baseModel->primaryKey, array(
							'sort_order' => $sortOrder
					));
				} else {
					$baseModel->updateByPk($baseModel->primaryKey, array(
							'sort_order' => ($baseModel->getMaxSortOrder() + 1)
					));
				}
			}

			echo CJSON::encode(array('result' => true));
			Yii::app()->end();
		}

		$this->layout = false;
		$this->render('sortSpecify');
	}

	public function actionUpdateSortOrder() {
		if (Yii::app()->user->checkAccess('updateServer') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$sortOrder = Yii::app()->request->getQuery('sort_order');
			$banner = Server::model()->multilingual()->findByPk($id);
			if (is_null($banner) == false) {
				$banner->sort_order = $sortOrder;
				$banner->save();
			}
		}
	}

	// 单个推荐
	public function actionCommend() {
		
		$id = Yii::app()->request->getQuery('id');
		$server = Server::model()->multilingual()->findByPk($id);
		if (is_null($server) == false) {
			$server->is_recommend = 1;
			$server->save();
			$this->setFlashMessage('服务推荐成功');
		}else{
			$this->setFlashMessage('服务推荐失败', 'warn');
		}
		$this->redirect(array('index'));
	}

	// 批量推荐
	public function actionBatchCommend() {
		
		$idList = Yii::app()->request->getPost('server_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('server_id', $idList);
			$servers = Server::model()->findAll($criteria);
			$flag = 0;
			foreach ($servers as $server) {
				$server->is_recommend = 1;
				if ($server->save()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('服务推荐成功');
			} else {
				$this->setFlashMessage('服务推荐失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

	// 批量不推荐
	public function actionBatchUnCommend() {

		$idList = Yii::app()->request->getPost('server_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('server_id', $idList);
			$servers = Server::model()->findAll($criteria);
			$flag = 0;
			foreach ($servers as $server) {
				$server->is_recommend = 0;
				if ($server->save()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('服务取消推荐成功');
			} else {
				$this->setFlashMessage('服务取消推荐失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}
}

?>