<?php
header('Content-Type: text/html; charset=utf-8');
class BannerController extends Controller {
	public function actionIndex() {
		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$bannerPositionId = intval(
				Yii::app()->request->getQuery('banner_position_id'));
		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.banner_id";
		if ($bannerPositionId > 0) {
			$criteria->compare('banner_position_id', $bannerPositionId);
		}
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('banner_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.title', $keyword, true, 'OR');
				$criteria
						->addSearchCondition('multilingual.title', $keyword,
								true, 'OR');
			}
		}

		$dataProvider = new CActiveDataProvider(
				Banner::model()->multilingual()->together(),
				array('criteria' => $criteria,
						'sort' => array(
								'defaultOrder' => 'sort_order ASC'),
						'pagination' => array('pageSize' => $pageSize)));

		$bannerPositionOptions = BannerPosition::model()->getOptions();

		$this->breadcrumbs = array('Banner');

		$this
				->render('index',
						array('dataProvider' => $dataProvider,
								'keyword' => $keyword,
								'bannerPositionId' => $bannerPositionId,
								'bannerPositionOptions' => $bannerPositionOptions));
	}

	public function actionCreate() {
		$model = new Banner();

		if (isset($_POST['Banner'])) {
			$model->attributes = Yii::app()->request->getPost('Banner');
			$model->bannerFile = CUploadedFile::getInstance($model,
					'bannerFile');
			$model->sort_order = $model->getMaxSortOrder() + 1;
			foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) {
				$model->i18nFormData['bannerFile_' . $lang] = CUploadedFile::getInstance(
						$model, 'i18nFormData[bannerFile_' . $lang . ']');
			}
			if ($model->save()) {
				$this
						->setFlashMessage(
								strtr('<strong>{link}</strong> Banner添加成功',
										array(
												'{link}' => CHtml::link(
														htmlspecialchars(
																$model->title),
														array('view',
																'id' => $model
																		->primaryKey)))));
				$this->redirect(array('index'));
			}
		} else {
			$model->i18nFormData = $model->getI18nAttributes();
		}

		$bannerPositionOptions = BannerPosition::model()->getOptions();

		$this->breadcrumbs = array('Banner' => array('index'), '添加');
		$this
				->render('create',
						array('model' => $model,
								'bannerPositionOptions' => $bannerPositionOptions));
	}

	public function actionUpdate() {
		$id = Yii::app()->request->getQuery('id');
		$model = Banner::model()->multilingual()->findByPk($id);
		if (empty($model))
			throw new CHttpException(404);

		if (isset($_POST['Banner'])) {
			$model->attributes = Yii::app()->request->getPost('Banner');
			$model->bannerFile = CUploadedFile::getInstance($model,
					'bannerFile');
			foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) {
				$model->i18nFormData['bannerFile_' . $lang] = CUploadedFile::getInstance(
						$model, 'i18nFormData[bannerFile_' . $lang . ']');
			}

			if ($model->save()) {
				$this
						->setFlashMessage(
								strtr('<strong>{link}</strong> Banner修改成功',
										array(
												'{link}' => CHtml::link(
														htmlspecialchars(
																$model->title),
														array('view',
																'id' => $model
																		->primaryKey)))));
				$this->redirect(array('index'));
			}
		} else {
			$model->i18nFormData = $model->getI18nAttributes();
		}

		$bannerPositionOptions = BannerPosition::model()->getOptions();

		$this->breadcrumbs = array('Banner' => array('index'), '修改');

		$this
				->render('create',
						array('model' => $model,
								'bannerPositionOptions' => $bannerPositionOptions));
	}

	public function actionUpdateIsReleased() {
		$id = Yii::app()->request->getPost('id');
		$language = Yii::app()->request->getPost('language');
		$isReleased = Yii::app()->request->getPost('is_released');
		if ($language == I18nHelper::getFrontendSourceLanguage()) {
			echo Banner::model()
					->updateByPk($id, array('is_released' => $isReleased));
		} else {
			echo BannerI18n::model()
					->updateAll(array('is_released' => $isReleased),
							'owner_id = :owner_id AND lang = :lang',
							array(':owner_id' => $id, ':lang' => $language));
		}
	}

	public function actionView() {
		$this
				->redirect(
						array('update',
								'id' => Yii::app()->request->getQuery('id')));
	}

	public function actionDelete() {
		if (Yii::app()->request->isAjaxRequest) {
			$id = Yii::app()->request->getQuery('id');
			$model = Banner::model()->multilingual()->findByPk($id);
			if (empty($model) == false)
				$model->delete();
		}
	}

	public function actionMultipleDelete() {
		$idList = Yii::app()->request->getPost('banner_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('banner_id', $idList);
			$models = Banner::model()->findAll($criteria);
			$flag = 0;
			foreach ($models as $model) {
				if ($model->delete()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('Banner 已成功删除');
			} else {
				$this->setFlashMessage('Banner 删除失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

	public function actionSortFirst() {
		$id = Yii::app()->request->getQuery('id');
		$model = Banner::model()->findByPk($id);
		if (empty($model) == false) {
			$sortOrder = $model->getMinSortOrder();
			if ($sortOrder < 2) {
				$model
						->updateAll(
								array(
										'sort_order' => new CDbExpression(
												'sort_order + 1')));
			}

			$model
					->updateByPk($model->primaryKey,
							array('sort_order' => $sortOrder - 1));
		}
		$this->redirect(array('index'));
	}

	public function actionSortPrevious() {
		$id = Yii::app()->request->getQuery('id');
		$model = Banner::model()->findByPk($id);
		if (empty($model) == false) {
			$sortOrder = $model->getPreviousSortOrder();
			if ($sortOrder > 0) {
				$model
						->updateAll(array('sort_order' => $model->sort_order),
								'sort_order = :sort_order',
								array('sort_order' => $sortOrder));
				$model
						->updateByPk($model->primaryKey,
								array('sort_order' => $sortOrder));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortNext() {
		$id = Yii::app()->request->getQuery('id');
		$model = Banner::model()->findByPk($id);
		if (empty($model) == false) {
			$sortOrder = $model->getNextSortOrder();
			if ($sortOrder > 0) {
				$model
						->updateAll(array('sort_order' => $model->sort_order),
								'sort_order = :sort_order',
								array('sort_order' => $sortOrder));
				$model
						->updateByPk($model->primaryKey,
								array('sort_order' => $sortOrder));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortLast() {
		$id = Yii::app()->request->getQuery('id');
		$model = Banner::model()->findByPk($id);
		if (empty($model) == false) {
			$model
					->updateByPk($model->primaryKey,
							array('sort_order' => $model->getMaxSortOrder() + 1));
		}
		$this->redirect(array('index'));
	}

	public function actionSortSpecify() {
		if (isset($_POST['target_id']) && isset($_POST['pos'])) {
			$id = Yii::app()->request->getQuery('id');
			$targetId = Yii::app()->request->getPost('target_id');
			$pos = Yii::app()->request->getPost('pos', '1');

			$baseModel = Banner::model()->findByPk($id);
			if (empty($baseModel)) {
				echo CJSON::encode(
						array('result' => false, 'message' => '操作对象不存在'));
				Yii::app()->end();
			}

			$targetModel = Banner::model()->findByPk($targetId);
			if (empty($targetModel)) {
				echo CJSON::encode(
						array('result' => false, 'message' => '编号不存在'));
				Yii::app()->end();
			}

			if ($pos == '1') {
				$sortOrder = $targetModel->getPreviousSortOrder();
				if ($sortOrder > 0) {
					$baseModel
							->updateAll(
									array(
											'sort_order' => new CDbExpression(
													'sort_order + 1')),
									'sort_order > :sort_order',
									array(':sort_order' => $sortOrder));
					$baseModel
							->updateByPk($baseModel->primaryKey,
									array(
											'sort_order' => $targetModel
													->sort_order));
				} else {
					$baseModel
							->updateAll(
									array(
											'sort_order' => new CDbExpression(
													'sort_order + 1')));
					$baseModel
							->updateByPk($baseModel->primaryKey,
									array(
											'sort_order' => $baseModel
													->getMinSortOrder() - 1));
				}
			} else {
				$sortOrder = $targetModel->getNextSortOrder();
				if ($sortOrder > 0) {
					$targetModel
							->updateAll(
									array(
											'sort_order' => new CDbExpression(
													'sort_order + 1')),
									'sort_order > :sort_order',
									array(
											':sort_order' => $targetModel
													->sort_order));
					$baseModel
							->updateByPk($baseModel->primaryKey,
									array('sort_order' => $sortOrder));
				} else {
					$baseModel
							->updateByPk($baseModel->primaryKey,
									array(
											'sort_order' => $baseModel
													->getMaxSortOrder() + 1));
				}
			}

			echo CJSON::encode(array('result' => true));
			Yii::app()->end();
		}

		$this->layout = false;
		$this->render('sortSpecify');
	}
}

?>