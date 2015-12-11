<?php

class BannerPositionController extends Controller {
	public function actionIndex() {
		$criteria = new CDbCriteria();
		$dataProvider = new CActiveDataProvider('BannerPosition',
				array('criteria' => $criteria,
						'sort' => array('defaultOrder' => 'title ASC'),
						'pagination' => array('pageSize' => 20)));

		$this->breadcrumbs = array('Banner 位置');
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	public function actionCreate() {
		$model = new BannerPosition();
		if ($_POST) {
			$model->setAttributes($_POST['BannerPosition']);
			if ($model->save()) {
				$this->setFlashMessage('添加 Banner 位置成功');
				$this->redirect(array('index'));
			}
		}

		$this->breadcrumbs = array('Banner 位置' => array('index'), '添加');
		$this->render('create', array('model' => $model,));
	}

	public function actionUpdate() {
		$model = BannerPosition::model()->findByPk($_GET['id']);
		if ($_POST) {
			$model->setAttributes($_POST['BannerPosition']);
			if ($model->save()) {
				$this->setFlashMessage('Banne分类已修改');
				$this->redirect(array('index'));
			}
		}

		$this->breadcrumbs = array('Banner 位置' => array('index'), '修改');
		$this->render('create', array('model' => $model,));
	}

	public function actionDelete() {
		$model = BannerPosition::model()->findByPk($_GET['id']);
		if (!is_null($model))
			$model->delete();
	}
}

?>