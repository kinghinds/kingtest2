<?php

class FeedbackController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewFeedback') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.id";
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.content', $keyword, true, 'OR');
			}
		}

		$dataProvider = new CActiveDataProvider(
				Feedback::model()->together(),
				array(
						'criteria' => $criteria,
						'sort' => array(
								'defaultOrder' => 'sort_order ASC, id DESC'
						),
						'pagination' => array('pageSize' => $pageSize)
				)
		);

		$this->breadcrumbs = array('问题咨询');

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
		));
	}

	public function actionView($id) {
		if (Yii::app()->user->checkAccess('viewFeedback') == false) {
			throw new CHttpException(403);
		}

		$feedback = Feedback::model()->findByPk($id);
		$answer = Answer::model()->find('feedback_id=:feedbackID', array(':feedbackID'=>$id));
		if (is_null($feedback)) {
			throw new CHttpException(404);
		}

		$this->breadcrumbs = array(
				'问题咨询' => array('index'), 
				'查看'
		);

		$this->render('view', array(
				'feedback' => $feedback,
				'answer' => $answer,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionDelete() {
		//判断是否有权限执行下面的代码
		if (Yii::app()->user->checkAccess('deleteFeedback') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAjaxRequest) {
			$id = Yii::app()->request->getQuery('id');
			$feedback = Feedback::model()->findByPk($id);
			
			if (empty($feedback) == false){
				$feedback->delete();
				$answer = Answer::model()->deleteAll('feedback_id=:feedbackID', array(':feedbackID'=>$id));
			}
		}
	}

	public function actionBatchDelete() {
		if (Yii::app()->user->checkAccess('deleteFeedback') == false) {
			throw new CHttpException(403);
		}

		$idList = Yii::app()->request->getPost('id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('id', $idList);
			$feedbacks = Feedback::model()->findAll($criteria);
			$flag = 0;
			foreach ($feedbacks as $feedback) {
				if ($feedback->delete()) {
					$answer = Answer::model()->deleteAll('feedback_id=:feedbackID', array(':feedbackID'=>$feedback->primaryKey));
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('问题咨询 已成功删除');
			} else {
				$this->setFlashMessage('问题咨询 删除失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

	public function actionSortFirst() {
		if (Yii::app()->user->checkAccess('updateFeedback') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$feedback = Feedback::model()->findByPk($id);
		if (empty($feedback) == false) {
			$sortOrder = $feedback->getMinSortOrder();
			if ($sortOrder < 2) {
				$feedback->updateAll(array(
					'sort_order' => new CDbExpression('sort_order + 1')
				));
			}
			$feedback->updateByPk($feedback->primaryKey, array(
				'sort_order' => ($sortOrder - 1)
			));
		}
		$this->redirect(array('index'));
	}

	public function actionSortPrevious() {
		if (Yii::app()->user->checkAccess('updateFeedback') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$feedback = Feedback::model()->findByPk($id);
		if (empty($feedback) == false) {
			$sortOrder = $feedback->getPreviousSortOrder();
			if ($sortOrder > 0) {
				$feedback->updateAll(
						array('sort_order' => $feedback->sort_order),
						'sort_order = :sort_order',
						array('sort_order' => $sortOrder)
				);
				$feedback->updateByPk($feedback->primaryKey, array(
						'sort_order' => $sortOrder
				));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortNext() {
		if (Yii::app()->user->checkAccess('updateFeedback') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$feedback = Feedback::model()->findByPk($id);
		if (empty($feedback) == false) {
			$sortOrder = $feedback->getNextSortOrder();
			if ($sortOrder > 0) {
				$feedback->updateAll(
						array('sort_order' => $feedback->sort_order),
						'sort_order = :sort_order',
						array('sort_order' => $sortOrder)
				);
				$feedback->updateByPk($feedback->primaryKey, array(
						'sort_order' => $sortOrder
				));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortLast() {
		if (Yii::app()->user->checkAccess('updateFeedback') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$feedback = Feedback::model()->findByPk($id);
		if (empty($feedback) == false) {
			$feedback->updateByPk($feedback->primaryKey, array(
					'sort_order' => ($feedback->getMaxSortOrder() + 1)
			));
		}
		$this->redirect(array('index'));
	}

	public function actionSortSpecify() {
		if (Yii::app()->user->checkAccess('updateFeedback') == false) {
			throw new CHttpException(403);
		}
		
		if (isset($_POST['target_id']) && isset($_POST['pos'])) {
			$id = Yii::app()->request->getQuery('id');
			$targetId = Yii::app()->request->getPost('target_id');
			$pos = Yii::app()->request->getPost('pos', '1');

			$baseModel = Feedback::model()->findByPk($id);
			if (empty($baseModel)) {
				echo CJSON::encode(array(
						'result' => false, 
						'message' => '操作对象不存在'
				));
				Yii::app()->end();
			}

			$targetModel = Feedback::model()->findByPk($targetId);
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
		if (Yii::app()->user->checkAccess('updateFeedback') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$sortOrder = Yii::app()->request->getQuery('sort_order');
			$feedback = Feedback::model()->findByPk($id);
			if (is_null($feedback) == false) {
				$feedback->sort_order = $sortOrder;
				$feedback->save();
			}
		}
	}
}

?>