<?php
header("Content-type:text/html;charset=utf-8");
/**
 * @memo   产品系列控制器
 * @author 邓 流 <759371065@qq.com>
 * @time   2015-02-27 10:17:45
 */
class ProductSeriesController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewProductSeries') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.series_id";
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('series_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.title', $keyword, true, 'OR');
			}
		}

		$dataProvider = new CActiveDataProvider(
				ProductSeries::model()->multilingual()->together(), array(
						'criteria' => $criteria,
						'sort' => array(
								'defaultOrder' => 't.sort_order ASC'
						),
						'pagination' => array('pageSize' => $pageSize)
				)
		);
		$this->breadcrumbs = array('产品系列');

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
		));
	}

	public function actionCreate() {
		if (Yii::app()->user->checkAccess('createProductSeries') == false) {
			throw new CHttpException(403);
		}

		$series = new ProductSeries('create');
		if (isset($_POST['ProductSeries'])) {
			$series->attributes = Yii::app()->request->getPost('ProductSeries');
			$series->create_time = time();
			$series->sort_order = $series->getMaxSortOrder() + 1;
			if ($series->validate() && $series->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 产品系列添加成功', array(
								'{link}' => CHtml::link($series->title, array(
									'view', 'id' => $series->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		}

		$this->breadcrumbs = array(
				'产品系列' => array('index'), 
				'添加'
		);
		$productCategoryOptions = ProductCategory::model()->getLeaveOptions();
		$brandOptions = Brand::model()->getOptions();
		$this->render('create', array(
				'series' => $series, 
				'productCategoryOptions'=> $productCategoryOptions,
				'brandOptions'=> $brandOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdate($id) {
		if (Yii::app()->user->checkAccess('updateProductSeries') == false) {
			throw new CHttpException(403);
		}

		$series = ProductSeries::model()->multilingual()->findByPk($id);
		if (is_null($series)) {
			throw new CHttpException(404);
		}

		if (isset($_POST['ProductSeries'])) {
			$series->attributes = Yii::app()->request->getPost('ProductSeries');
			if ($series->validate() && $series->save()) {

				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 产品系列修改成功', array(
								'{link}' => CHtml::link($series->title, array(
										'view', 'id' => $series->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		} 

		$this->breadcrumbs = array(
				'产品系列' => array('index'), 
				'修改'
		);
		$productCategoryOptions = ProductCategory::model()->getLeaveOptions();
		$brandOptions = Brand::model()->getOptions();
		$this->render('create', array(
				'series' => $series, 
				'productCategoryOptions'=> $productCategoryOptions,
				'brandOptions'=> $brandOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdateIsReleased() {
		if (Yii::app()->user->checkAccess('updateProductSeries') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getPost('id');
		$language = Yii::app()->request->getPost('language');
		$isReleased = Yii::app()->request->getPost('is_released');
		if ($language == I18nHelper::getFrontendSourceLanguage()) {
			echo ProductSeries::model()->updateByPk($id, array(
					'is_released' => $isReleased
			));
		} else {
			echo ProductSeriesI18n::model()->updateAll(
					array('is_released' => $isReleased),
					'owner_id = :owner_id AND lang = :lang',
					array(':owner_id' => $id, ':lang' => $language)
			);
		}
	}

	public function actionDelete() {
		if (Yii::app()->user->checkAccess('deleteProductSeries') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$series = ProductSeries::model()->multilingual()->findByPk($id);
			if (is_null($series) == false) {
				$series->delete();
			}
		}
	}

	public function actionBatchDelete() {
		if (Yii::app()->user->checkAccess('deleteProductSeries') == false) {
			throw new CHttpException(403);
		}
		
		$idList = Yii::app()->request->getPost('series_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('series_id', $idList);
			$seriess = ProductSeries::model()->findAll($criteria);
			$flag = 0;
			foreach ($seriess as $series) {
				if ($series->delete()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('产品系列已成功删除');
			} else {
				$this->setFlashMessage('产品系列删除失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

}

?>