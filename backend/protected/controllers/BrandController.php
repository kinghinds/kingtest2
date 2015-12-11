<?php

class BrandController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewBrand') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.brand_id";
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('brand_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.title', $keyword, true, 'OR');
				$criteria->addSearchCondition('multilingual.title', $keyword,
						true, 'OR');
			}
		}

		$dataProvider = new CActiveDataProvider(
				Brand::model()->multilingual()->together(),
				array(
						'criteria' => $criteria,
						'sort' => array(
								'defaultOrder' => 'sort_order ASC'
						),
						'pagination' => array('pageSize' => $pageSize)
				)
		);

		$this->breadcrumbs = array('品牌');

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
		));
	}

	public function actionView($id) {
		if (Yii::app()->user->checkAccess('viewBrand') == false) {
			throw new CHttpException(403);
		}

		$brand = Brand::model()->multilingual()->findByPk($id);
		if (is_null($brand)) {
			throw new CHttpException(404);
		}

		$this->breadcrumbs = array(
				'品牌' => array('index'), 
				'查看'
		);

		$this->render('view', array(
				'brand' => $brand,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionCreate() {
		if (Yii::app()->user->checkAccess('createBrand') == false) {
			throw new CHttpException(403);
		}

		$brand = new Brand();

		if (isset($_POST['Brand'])) {
			$brand->attributes = Yii::app()->request->getPost('Brand');
			$brand->brandFile = CUploadedFile::getInstance($brand, 
					'brandFile');			
			foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) {
				$brand->i18nFormData['brandFile_' . $lang] = 
						CUploadedFile::getInstance($brand, 
								'i18nFormData[brandFile_' . $lang . ']');
			}
			$brand->sort_order = $brand->getMaxSortOrder() + 1;
			if ($brand->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 品牌添加成功', array(
								'{link}' => CHtml::link(
										CHtml::encode($brand->title),
										array('view', 'id' => $brand->primaryKey)
								)
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		} 

		$regionOptions = Region::model()->getOptions();

		$this->breadcrumbs = array(
				'品牌' => array('index'), 
				'添加'
		);

		$this->render('create', array(
				'brand' => $brand,
				'regionOptions' => $regionOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdate($id) {
		if (Yii::app()->user->checkAccess('updateBrand') == false) {
			throw new CHttpException(403);
		}

		$brand = Brand::model()->multilingual()->findByPk($id);
		if (is_null($brand)) {
			throw new CHttpException(404);
		}

		if (isset($_POST['Brand'])) {
			$brand->attributes = Yii::app()->request->getPost('Brand');
			$brand->brandFile = CUploadedFile::getInstance($brand, 
					'brandFile');			
			foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) {
				$brand->i18nFormData['brandFile_' . $lang] = 
						CUploadedFile::getInstance($brand, 
								'i18nFormData[brandFile_' . $lang . ']');
			}
			if ($brand->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 品牌修改成功', array(
								'{link}' => CHtml::link(
										CHtml::encode($brand->title),
										array('view', 'id' => $brand->primaryKey)
								)
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		}

		$regionOptions = Region::model()->getOptions();

		$this->breadcrumbs = array(
				'品牌' => array('index'), 
				'修改'
		);

		$this->render('create', array(
				'brand' => $brand,
				'regionOptions' => $regionOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdateIsReleased() {
		if (Yii::app()->user->checkAccess('updateBrand') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getPost('id');
		$language = Yii::app()->request->getPost('language');
		$isReleased = Yii::app()->request->getPost('is_released');
		if ($language == I18nHelper::getFrontendSourceLanguage()) {
			echo Brand::model()->updateByPk($id, array(
					'is_released' => $isReleased
			));
		} else {
			echo BrandI18n::model()->updateAll(
					array('is_released' => $isReleased),
					'owner_id = :owner_id AND lang = :lang',
					array(':owner_id' => $id, ':lang' => $language)
			);
		}
	}

	public function actionDelete() {
		if (Yii::app()->user->checkAccess('deleteBrand') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAjaxRequest) {
			$id = Yii::app()->request->getQuery('id');
			$brand = Brand::model()->multilingual()->findByPk($id);
			if (empty($brand) == false)
				$brand->delete();
		}
	}

	public function actionBatchDelete() {
		if (Yii::app()->user->checkAccess('deleteBrand') == false) {
			throw new CHttpException(403);
		}

		$idList = Yii::app()->request->getPost('brand_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('brand_id', $idList);
			$brands = Brand::model()->findAll($criteria);
			$flag = 0;
			foreach ($brands as $brand) {
				if ($brand->delete()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('品牌 已成功删除');
			} else {
				$this->setFlashMessage('品牌 删除失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

	public function actionSortFirst() {
		if (Yii::app()->user->checkAccess('updateBrand') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$brand = Brand::model()->findByPk($id);
		if (empty($brand) == false) {
			$sortOrder = $brand->getMinSortOrder();
			if ($sortOrder < 2) {
				$brand->updateAll(array(
					'sort_order' => new CDbExpression('sort_order + 1')
				));
			}
			$brand->updateByPk($brand->primaryKey, array(
				'sort_order' => ($sortOrder - 1)
			));
		}
		$this->redirect(array('index'));
	}

	public function actionSortPrevious() {
		if (Yii::app()->user->checkAccess('updateBrand') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$brand = Brand::model()->findByPk($id);
		if (empty($brand) == false) {
			$sortOrder = $brand->getPreviousSortOrder();
			if ($sortOrder > 0) {
				$brand->updateAll(
						array('sort_order' => $brand->sort_order),
						'sort_order = :sort_order',
						array('sort_order' => $sortOrder)
				);
				$brand->updateByPk($brand->primaryKey, array(
						'sort_order' => $sortOrder
				));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortNext() {
		if (Yii::app()->user->checkAccess('updateBrand') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$brand = Brand::model()->findByPk($id);
		if (empty($brand) == false) {
			$sortOrder = $brand->getNextSortOrder();
			if ($sortOrder > 0) {
				$brand->updateAll(
						array('sort_order' => $brand->sort_order),
						'sort_order = :sort_order',
						array('sort_order' => $sortOrder)
				);
				$brand->updateByPk($brand->primaryKey, array(
						'sort_order' => $sortOrder
				));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortLast() {
		if (Yii::app()->user->checkAccess('updateBrand') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$brand = Brand::model()->findByPk($id);
		if (empty($brand) == false) {
			$brand->updateByPk($brand->primaryKey, array(
					'sort_order' => ($brand->getMaxSortOrder() + 1)
			));
		}
		$this->redirect(array('index'));
	}

	public function actionSortSpecify() {
		if (Yii::app()->user->checkAccess('updateBrand') == false) {
			throw new CHttpException(403);
		}
		
		if (isset($_POST['target_id']) && isset($_POST['pos'])) {
			$id = Yii::app()->request->getQuery('id');
			$targetId = Yii::app()->request->getPost('target_id');
			$pos = Yii::app()->request->getPost('pos', '1');

			$baseModel = Brand::model()->findByPk($id);
			if (empty($baseModel)) {
				echo CJSON::encode(array(
						'result' => false, 
						'message' => '操作对象不存在'
				));
				Yii::app()->end();
			}

			$targetModel = Brand::model()->findByPk($targetId);
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
		if (Yii::app()->user->checkAccess('updateBrand') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$sortOrder = Yii::app()->request->getQuery('sort_order');
			$brand = Brand::model()->multilingual()->findByPk($id);
			if (is_null($brand) == false) {
				$brand->sort_order = $sortOrder;
				$brand->save();
			}
		}
	}

	// 单个推荐
	public function actionCommend() {
		
		$id = Yii::app()->request->getQuery('id');
		$brand = Brand::model()->multilingual()->findByPk($id);
		if (is_null($brand) == false) {
			$brand->is_recommend = 1;
			$brand->save();
			$this->setFlashMessage('品牌推荐成功');
		}else{
			$this->setFlashMessage('品牌推荐失败', 'warn');
		}
		$this->redirect(array('index'));
	}

	// 批量推荐
	public function actionBatchCommend() {
		
		$idList = Yii::app()->request->getPost('brand_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('brand_id', $idList);
			$brands = Brand::model()->findAll($criteria);
			$flag = 0;
			foreach ($brands as $brand) {
				$brand->is_recommend = 1;
				if ($brand->save()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('品牌推荐成功');
			} else {
				$this->setFlashMessage('品牌推荐失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

	// 批量不推荐
	public function actionBatchUnCommend() {

		$idList = Yii::app()->request->getPost('brand_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('brand_id', $idList);
			$brands = Brand::model()->findAll($criteria);
			$flag = 0;
			foreach ($brands as $brand) {
				$brand->is_recommend = 0;
				if ($brand->save()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('品牌取消推荐成功');
			} else {
				$this->setFlashMessage('品牌取消推荐失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}
}

?>