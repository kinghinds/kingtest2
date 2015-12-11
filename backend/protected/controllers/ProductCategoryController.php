<?php
header("Content-type:text/html;charset=utf-8");
/**
 * @memo   产品类型控制器
 * @author 邓 流 <759371065@qq.com>
 * @time   2015-02-27 10:17:45
 */
class ProductCategoryController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewProductCategory') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.category_id";
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('category_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.name', $keyword, true, 'OR');
			}
		}

		$dataProvider = new CActiveDataProvider(
				ProductCategory::model()->multilingual()->together(), array(
						'criteria' => $criteria,
						'sort' => array(
								'defaultOrder' => 't.sort_order ASC'
						),
						'pagination' => array('pageSize' => $pageSize)
				)
		);
		$this->breadcrumbs = array('产品类型');

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
		));
	}

	public function actionView($id) {
		if (Yii::app()->user->checkAccess('viewProductCategory') == false) {
			throw new CHttpException(403);
		}

		$category = ProductCategory::model()->multilingual()->findByPk($id);
		if (is_null($category)) {
			throw new CHttpException(404);
		}

		$this->breadcrumbs = array(
				'产品类型' => array('index'), 
				'查看'
		);

		$this->render('view', array(
				'category' => $category,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionCreate() {
		if (Yii::app()->user->checkAccess('createProductCategory') == false) {
			throw new CHttpException(403);
		}

		$category = new ProductCategory('create');
		if (isset($_POST['ProductCategory'])) {
			$category->attributes = Yii::app()->request->getPost('ProductCategory');
			$category->create_time = time();
			$category->update_time = time();
			$category->sort_order = $category->getMaxSortOrder() + 1;
			foreach ($category->brand_id as $key => $value) {
				$brandname[] = Brand::model()->findByPk($value)->title;
			}

			$category->brand_id = implode(',', $category->brand_id);
			$category->brand_name = implode(',  ', $brandname);
			if ($category->validate() && $category->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 产品类型添加成功', array(
								'{link}' => CHtml::link($category->name, array(
									'view', 'id' => $category->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		}

		$this->breadcrumbs = array(
				'产品类型' => array('index'), 
				'添加'
		);
		$productCategoryOptions = ProductCategory::model()->byBrandOptions(0);
		$brandOptions = Brand::model()->getOptions();
		$this->render('create', array(
				'category' => $category, 
				'productCategoryOptions'=> $productCategoryOptions,
				'brandOptions'=> $brandOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdate($id) {
		if (Yii::app()->user->checkAccess('updateProductCategory') == false) {
			throw new CHttpException(403);
		}

		$category = ProductCategory::model()->multilingual()->findByPk($id);
		if (is_null($category)) {
			throw new CHttpException(404);
		}

		if (isset($_POST['ProductCategory'])) {
			$category->attributes = Yii::app()->request->getPost('ProductCategory');
			$category->update_time = time();
			foreach ($category->brand_id as $key => $value) {
				$brandname[] = Brand::model()->findByPk($value)->title;
			}

			$category->brand_id = implode(',', $category->brand_id);
			$category->brand_name = implode(',  ', $brandname);
			if ($category->validate() && $category->save()) {

				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 产品类型修改成功', array(
								'{link}' => CHtml::link($category->name, array(
										'view', 'id' => $category->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		} 

		$this->breadcrumbs = array(
				'产品类型' => array('index'), 
				'修改'
		);
		$productCategoryOptions = ProductCategory::model()->getOptions();
		$brandOptions = Brand::model()->getOptions();
		$this->render('create', array(
				'category' => $category, 
				'productCategoryOptions'=> $productCategoryOptions,
				'brandOptions'=> $brandOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdateIsReleased() {
		if (Yii::app()->user->checkAccess('updateProductCategory') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getPost('id');
		$language = Yii::app()->request->getPost('language');
		$isReleased = Yii::app()->request->getPost('is_released');
		if ($language == I18nHelper::getFrontendSourceLanguage()) {
			echo ProductCategory::model()->updateByPk($id, array(
					'is_released' => $isReleased
			));
		} else {
			echo ProductCategoryI18n::model()->updateAll(
					array('is_released' => $isReleased),
					'owner_id = :owner_id AND lang = :lang',
					array(':owner_id' => $id, ':lang' => $language)
			);
		}
	}

	public function actionDelete() {
		if (Yii::app()->user->checkAccess('deleteProductCategory') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$category = ProductCategory::model()->multilingual()->findByPk($id);
			if (is_null($category) == false) {
				$category->delete();
			}
		}
	}

	public function actionBatchDelete() {
		if (Yii::app()->user->checkAccess('deleteProductCategory') == false) {
			throw new CHttpException(403);
		}
		
		$idList = Yii::app()->request->getPost('category_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('category_id', $idList);
			$categorys = ProductCategory::model()->findAll($criteria);
			$flag = 0;
			foreach ($categorys as $category) {
				if ($category->delete()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('产品类型已成功删除');
			} else {
				$this->setFlashMessage('产品类型删除失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

}

?>