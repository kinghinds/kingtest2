<?php
header("Content-type:text/html;charset=utf-8");
/**
 * @memo   品牌地区控制器
 * @author 邓 流 <759371065@qq.com>
 * @time   2015-02-27 10:17:45
 */
class RegionController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewRegion') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.region_id";
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('region_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.title', $keyword, true, 'OR');
			}
		}

		$dataProvider = new CActiveDataProvider(
				Region::model()->multilingual()->together(), array(
						'criteria' => $criteria,
						'sort' => array(
								'defaultOrder' => 't.sort_order ASC'
						),
						'pagination' => array('pageSize' => $pageSize)
				)
		);
		$this->breadcrumbs = array('品牌地区');

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
		));
	}

	public function actionView($id) {
		if (Yii::app()->user->checkAccess('viewRegion') == false) {
			throw new CHttpException(403);
		}

		$data = Region::model()->multilingual()->findByPk($id);
		if (is_null($data)) {
			throw new CHttpException(404);
		}

		$this->breadcrumbs = array(
				'品牌地区' => array('index'), 
				'查看'
		);

		$this->render('view', array(
				'data' => $data,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionCreate() {
		if (Yii::app()->user->checkAccess('createRegion') == false) {
			throw new CHttpException(403);
		}

		$data = new Region();
		if (isset($_POST['Region'])) {
			$data->attributes = Yii::app()->request->getPost('Region');
			$data->sort_order = $data->getMaxSortOrder() + 1;
			if ($data->validate() && $data->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 品牌地区添加成功', array(
								'{link}' => CHtml::link($data->title, array(
									'view', 'id' => $data->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		}

		$this->breadcrumbs = array(
				'品牌地区' => array('index'), 
				'添加'
		);
		$this->render('create', array(
				'data' => $data, 
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdate($id) {
		if (Yii::app()->user->checkAccess('updateRegion') == false) {
			throw new CHttpException(403);
		}

		$data = Region::model()->multilingual()->findByPk($id);
		if (is_null($data)) {
			throw new CHttpException(404);
		}

		if (isset($_POST['Region'])) {
			$data->attributes = Yii::app()->request->getPost('Region');
			if ($data->validate() && $data->save()) {

				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 品牌地区修改成功', array(
								'{link}' => CHtml::link($data->title, array(
										'view', 'id' => $data->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		} 

		$this->breadcrumbs = array(
				'品牌地区' => array('index'), 
				'修改'
		);
		$this->render('create', array(
				'data' => $data, 
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionUpdateIsReleased() {
		if (Yii::app()->user->checkAccess('updateRegion') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getPost('id');
		$language = Yii::app()->request->getPost('language');
		$isReleased = Yii::app()->request->getPost('is_released');
		if ($language == I18nHelper::getFrontendSourceLanguage()) {
			echo Region::model()->updateByPk($id, array(
					'is_released' => $isReleased
			));
		} else {
			echo RegionI18n::model()->updateAll(
					array('is_released' => $isReleased),
					'owner_id = :owner_id AND lang = :lang',
					array(':owner_id' => $id, ':lang' => $language)
			);
		}
	}

	public function actionDelete() {
		if (Yii::app()->user->checkAccess('deleteRegion') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$data = Region::model()->multilingual()->findByPk($id);
			if (is_null($data) == false) {
				$data->delete();
			}
		}
	}

	public function actionBatchDelete() {
		if (Yii::app()->user->checkAccess('deleteRegion') == false) {
			throw new CHttpException(403);
		}
		
		$idList = Yii::app()->request->getPost('data_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('data_id', $idList);
			$datas = Region::model()->findAll($criteria);
			$flag = 0;
			foreach ($datas as $data) {
				if ($data->delete()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('品牌地区已成功删除');
			} else {
				$this->setFlashMessage('品牌地区删除失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

}

?>