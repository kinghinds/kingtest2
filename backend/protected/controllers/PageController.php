<?php

class PageController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewPage') == false) {
			throw new CHttpException(403);
		}

		$pages = Page::model()->getTree();
		$dataProvider = new CArrayDataProvider($pages, array(
				'keyField' => 'page_id',
				'pagination' => array('pageSize' => count($pages))
		));

		$this->breadcrumbs = array(
				'页面' => array('page'), 
				'目录'
		);

		$this->render('index', array(
				'dataProvider' => $dataProvider
		));
	}

	public function actionPage() {
		if (Yii::app()->user->checkAccess('viewPage') == false) {
			throw new CHttpException(403);
		}

		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.page_id";
		$criteria->compare('module_name', 'page');
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('page_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.title', $keyword, true, 'OR');
				/*$criteria->addSearchCondition('multilingual.title', $keyword, 
						true, 'OR');*/
			}
		}

		$dataProvider = new CActiveDataProvider(
				Page::model()->multilingual()->together(), array(
						'criteria' => $criteria,
						'sort' => array('defaultOrder' => 't.sort_order'),
						'pagination' => array('pageSize' => 10)
				)
		);

		$this->breadcrumbs = array('页面');

		$this->render('page', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword
		));
	}

	public function actionView($id) {
		if (Yii::app()->user->checkAccess('viewPage') == false) {
			throw new CHttpException(403);
		}
		$viewId = Yii::app()->request->getQuery('view_id');
		$page = Page::model()->multilingual()->findByPk($id);
		if (is_null($page)) {
			throw new CHttpException(404);
		}

		$this->breadcrumbs = array(
				'页面' => array('page'), 
				'查看'
		);
		$this->render('view', array(
				'page' => $page,
				'viewId' => $viewId,
				'returnUrl' => $this->getReturnUrl()
		));

	}

	public function actionCreate() {
		if (Yii::app()->user->checkAccess('createPage') == false) {
			throw new CHttpException(403);
		}
		$viewId = Yii::app()->request->getQuery('view_id');
		$page = new Page();
		if (isset($_POST['Page'])) {
			$page->attributes = Yii::app()->request->getPost('Page');
			$page->internal_link_keywords = Helper::arrangeKeywords($page->internal_link_keywords);
			$page->search_keywords = Helper::arrangeKeywords($page->search_keywords);
			$page->sort_order = $page->getMaxSortOrder() + 1;
			$page->bannerFile = CUploadedFile::getInstance($page, 'bannerFile');
			foreach (I18nHelper::getFrontendLanguageKeys() as $lang) {
				$page->i18nFormData['bannerFile_' . $lang] = 
						CUploadedFile::getInstance($page, 
								'i18nFormData[bannerFile_' . $lang . ']');
			}
			$page->bgImageFile = CUploadedFile::getInstance($page, 'bgImageFile');			
			if ($page->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 目录添加成功', array(
								'{link}' => CHtml::link(
										$page->title,
										array('view', 'id' => $page->primaryKey)
								)
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		} else {
			$page->module_name = 'page';
		}

		$pageOptions = Page::model()->getOptions();
		$moduleOptions = Page::model()->getModuleOptions();
		$targetWindowOptions = Page::model()->getTargetWindowOptions();

		$this->breadcrumbs = array(
				'页面' => array('page'), 
				'添加'
		);

		$this->render('create', array(
				'page' => $page, 
				'viewId' => $viewId,
				'pageOptions' => $pageOptions,
				'moduleOptions' => $moduleOptions,
				'targetWindowOptions' => $targetWindowOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdate($id) {
		if (Yii::app()->user->checkAccess('updatePage') == false) {
			throw new CHttpException(403);
		}
		$viewId = Yii::app()->request->getQuery('view_id');
		$page = Page::model()->multilingual()->findByPk($id);
		if (is_null($page)) {
			throw new CHttpException(404);
		}

		if (isset($_POST['Page'])) {
			$page->attributes = Yii::app()->request->getPost('Page');
			$page->internal_link_keywords = Helper::arrangeKeywords($page->internal_link_keywords);
			$page->search_keywords = Helper::arrangeKeywords($page->search_keywords);
			$page->bannerFile = CUploadedFile::getInstance($page, 'bannerFile');
			foreach (I18nHelper::getFrontendLanguageKeys() as $lang) {
				$page->i18nFormData['bannerFile_' . $lang] = 
						CUploadedFile::getInstance($page, 
								'i18nFormData[bannerFile_' . $lang . ']');
			}
			$page->bgImageFile = CUploadedFile::getInstance($page, 'bgImageFile');
			if ($page->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 目录修改成功', array(
								'{link}' => CHtml::link(
										$page->title,
										array('view', 'id' => $page->primaryKey)
								)
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		} 

		$pageOptions = Page::model()->getOptions();
		$moduleOptions = Page::model()->getModuleOptions();
		$targetWindowOptions = Page::model()->getTargetWindowOptions();

		$this->breadcrumbs = array(
				'页面' => array('page'), 
				'修改'
		);

		$this->render('create', array(
				'page' => $page, 
				'viewId' => $viewId,
				'pageOptions' => $pageOptions,
				'moduleOptions' => $moduleOptions,
				'targetWindowOptions' => $targetWindowOptions,
				'returnUrl' => $this->getReturnUrl()			
		));
	}

	public function actionUpdateIsReleased() {
		if (Yii::app()->user->checkAccess('updatePage') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getPost('id');
		$language = Yii::app()->request->getPost('language');
		$isReleased = Yii::app()->request->getPost('is_released');
		if ($language == I18nHelper::getFrontendSourceLanguage()) {
			echo Page::model()->updateByPk($id, array(
					'is_released' => $isReleased
			));
		} else {
			echo PageI18n::model()->updateAll(
					array('is_released' => $isReleased),
					'owner_id = :owner_id AND lang = :lang',
					array(':owner_id' => $id, ':lang' => $language)
			);
		}
	}

	public function actionDelete() {
		if (Yii::app()->user->checkAccess('deletePage') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$page = Page::model()->findByPk($id);
		if (is_null($page)) {
			throw new CHttpException(404);
		}

		$returnUrl = Yii::app()->request->getQuery('return_url');
		if (empty($returnUrl)) {
			$returnUrl = array('index');
		}

		if ($page->childrenCount > 0) {
			$this->setFlashMessage('该目录存在子目录', 'warn');
			$this->redirect(array('index'));
		}
		if ($page->delete()) {
			$this->setFlashMessage('目录已成功删除');
			$this->redirect($returnUrl);
		} else {
			$this->redirectMessage('目录删除删除失败');
			$this->redirect(array('index'));
		}
	}

	public function actionSortFirst() {
		if (Yii::app()->user->checkAccess('updatePage') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$page = Page::model()->findByPk($id);
		if (empty($page) == false) {
			$sortOrder = $page->getMinSortOrder();
			$page->updateByPk($page->primaryKey, array(
					'sort_order' => $sortOrder - 1));
			if ($sortOrder - 1 <= 0) {
				$page->updateAll(array(
						'sort_order' => new CDbExpression('sort_order + 1')
				));
			} 
		}
		$this->redirect(array('index'));
	}

	public function actionSortPrevious() {
		if (Yii::app()->user->checkAccess('updatePage') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$page = Page::model()->findByPk($id);
		if (empty($page) == false) {
			$sortOrder = $page->getPreviousSortOrder();
			if ($sortOrder > 0) {
				$page->updateAll(
						array('sort_order' => $page->sort_order), 
						'sort_order = :sort_order', 
						array('sort_order' => $sortOrder)
				);
				$page->updateByPk($page->primaryKey, array(
						'sort_order' => $sortOrder
				));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortNext() {
		if (Yii::app()->user->checkAccess('updatePage') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$page = Page::model()->findByPk($id);
		if (is_null($page) == false) {
			$sortOrder = $page->getNextSortOrder();
			if ($sortOrder > 0) {
				$page->updateAll(
						array('sort_order' => $page->sort_order),
						'sort_order = :sort_order',
						array('sort_order' => $sortOrder)
				);
				$page->updateByPk($page->primaryKey, array(
						'sort_order' => $sortOrder
				));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortLast() {
		if (Yii::app()->user->checkAccess('updatePage') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$page = Page::model()->findByPk($id);
		if (is_null($page) == false) {
			$page->updateByPk($page->primaryKey, array(
					'sort_order' => ($page->getMaxSortOrder() + 1)
			));
		}
		$this->redirect(array('index'));
	}

	public function actionSortSpecify() {
		if (Yii::app()->user->checkAccess('updatePage') == false) {
			throw new CHttpException(403);
		}
		
		if (isset($_POST['target_id']) && isset($_POST['pos'])) {
			$id = Yii::app()->request->getQuery('id');
			$targetId = Yii::app()->request->getPost('target_id');
			$pos = Yii::app()->request->getPost('pos', '1');

			$baseModel = Page::model()->findByPk($id);
			if (is_null($baseModel)) {
				echo CJSON::encode(array(
						'result' => false, 
						'message' => '操作对象不存在'
				));
				Yii::app()->end();
			}

			$targetModel = Page::model()->findByPk($targetId);
			if (empty($targetModel)) {
				echo CJSON::encode(array(
						'result' => false, 
						'message' => '编号不存在'
				));
				Yii::app()->end();
			} else if ($baseModel->parent_id != $targetModel->parent_id) {
				echo CJSON::encode(array(
						'result' => false,
						'message' => '编号记录不在同一级目录下，无法进行排序操作'
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
		if (Yii::app()->user->checkAccess('updatePage') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$sortOrder = Yii::app()->request->getQuery('sort_order');
			$page = Page::model()->multilingual()->findByPk($id);
			if (is_null($page) == false) {
				$page->sort_order = $sortOrder;
				$page->save();
			}
		}
	}
}

?>