<?php

class AnswerController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewAnswer') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.answer_id";
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('answer_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.content', $keyword, true, 'OR');
			}
		}

		$dataProvider = new CActiveDataProvider(
				Answer::model()->together(),
				array(
						'criteria' => $criteria,
						'sort' => array(
								'defaultOrder' => 'answer_id DESC'
						),
						'pagination' => array('pageSize' => $pageSize)
				)
		);
		$this->breadcrumbs = array('咨询回复');

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
		));
	}

	public function actionReply($feedbackid){
		if (Yii::app()->user->checkAccess('createAnswer') == false) {
			throw new CHttpException(403);
		}
		$feedback = Feedback::model()->findByPk($feedbackid);
		$answer = new Answer();

		if (isset($_POST['Answer'])) {
			$answer->attributes = Yii::app()->request->getPost('Answer');
			$answer->reply_time = date('Y-m-d');
			$answer->feedback_id = $feedbackid;
			if ($answer->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 问题咨询回复成功', array(
								'{link}' => CHtml::link(
										CHtml::encode($feedback->content),
										array('view', 'id' => $answer->primaryKey)
								)
						)
				));
				$feedback->is_reply = 1;
				$feedback->save();
				$this->redirect(array('answer/index'));
			}
		} 

		$this->breadcrumbs = array(
				'问题咨询' => array('feedback/index'), 
				'回复'
		);

		$this->render('reply', array(
				'answer' => $answer,
				'feedback' =>$feedback,
		));
	}
	public function actionUpdate($id){
		if (Yii::app()->user->checkAccess('updateAnswer') == false) {
			throw new CHttpException(403);
		}
		$answer = Answer::model()->findByPk($id);
		$feedback = $answer->feedback;
		if (isset($_POST['Answer'])) {
			$answer->attributes = Yii::app()->request->getPost('Answer');
			$answer->reply_time = date('Y-m-d');
			$answer->feedback_id = $feedback->feedback_id;
			if ($answer->save()) {
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 咨询回复修改成功', array(
								'{link}' => CHtml::link(
										CHtml::encode($feedback->content),
										array('view', 'id' => $answer->primaryKey)
								)
						)
				));
				$this->redirect(array('answer/index'));
			}
		} 

		$this->breadcrumbs = array(
				'咨询回复' => array('index'), 
				'修改'
		);

		$this->render('reply', array(
				'answer' => $answer,
				'feedback' =>$feedback,
		));
	}
	public function actionView($id) {
		if (Yii::app()->user->checkAccess('viewAnswer') == false) {
			throw new CHttpException(403);
		}

		$answer = Answer::model()->findByPk($id);
		if (is_null($answer)) {
			throw new CHttpException(404);
		}

		$this->breadcrumbs = array(
				'咨询回复' => array('index'), 
				'查看'
		);

		$this->render('view', array(
				'answer' => $answer,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionDelete() {
		//判断是否有权限执行下面的代码
		if (Yii::app()->user->checkAccess('deleteAnswer') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAjaxRequest) {
			$id = Yii::app()->request->getQuery('id');
			$feedback = Answer::model()->findByPk($id);
			if (empty($feedback) == false)
				$feedback->delete();
		}
	}

	public function actionBatchDelete() {
		if (Yii::app()->user->checkAccess('deleteAnswer') == false) {
			throw new CHttpException(403);
		}

		$idList = Yii::app()->request->getPost('answer_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('answer_id', $idList);
			$feedbacks = Answer::model()->findAll($criteria);
			$flag = 0;
			foreach ($feedbacks as $feedback) {
				if ($feedback->delete()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('咨询回复 已成功删除');
			} else {
				$this->setFlashMessage('咨询回复 删除失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

	public function actionSortFirst() {
		if (Yii::app()->user->checkAccess('updateAnswer') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$feedback = Answer::model()->findByPk($id);
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
		if (Yii::app()->user->checkAccess('updateAnswer') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$feedback = Answer::model()->findByPk($id);
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
		if (Yii::app()->user->checkAccess('updateAnswer') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$feedback = Answer::model()->findByPk($id);
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
		if (Yii::app()->user->checkAccess('updateAnswer') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$feedback = Answer::model()->findByPk($id);
		if (empty($feedback) == false) {
			$feedback->updateByPk($feedback->primaryKey, array(
					'sort_order' => ($feedback->getMaxSortOrder() + 1)
			));
		}
		$this->redirect(array('index'));
	}

	public function actionSortSpecify() {
		if (Yii::app()->user->checkAccess('updateAnswer') == false) {
			throw new CHttpException(403);
		}
		
		if (isset($_POST['target_id']) && isset($_POST['pos'])) {
			$id = Yii::app()->request->getQuery('id');
			$targetId = Yii::app()->request->getPost('target_id');
			$pos = Yii::app()->request->getPost('pos', '1');

			$baseModel = Answer::model()->findByPk($id);
			if (empty($baseModel)) {
				echo CJSON::encode(array(
						'result' => false, 
						'message' => '操作对象不存在'
				));
				Yii::app()->end();
			}

			$targetModel = Answer::model()->findByPk($targetId);
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
		if (Yii::app()->user->checkAccess('updateAnswer') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$sortOrder = Yii::app()->request->getQuery('sort_order');
			$feedback = Answer::model()->findByPk($id);
			if (is_null($feedback) == false) {
				$feedback->sort_order = $sortOrder;
				$feedback->save();
			}
		}
	}
	public function actionReadExc(){
			spl_autoload_unregister(array('YiiBase', 'autoload'));
	        $phpExcelPath = Yii::getPathOfAlias('application.extensions.phpexcel');
			include($phpExcelPath.'/PHPExcel' . DIRECTORY_SEPARATOR . 'IOFactory.php');
			include($phpExcelPath.'/PHPExcel' . DIRECTORY_SEPARATOR . '/Writer/Excel5.php');
	        include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
	        spl_autoload_register(array('YiiBase', 'autoload'));
/*
			$answer = Answer::model()->findAll();
			$resultPHPExcel = new PHPExcel();
			$resultPHPExcel->getActiveSheet()->setCellValue('A1', '季度'); 
			$resultPHPExcel->getActiveSheet()->setCellValue('B1', '名称'); 
			$resultPHPExcel->getActiveSheet()->setCellValue('C1', '数量'); 
			$resultPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
			$i = 2; 
			foreach($answer as $item){ 
				$contents = str_replace('<p>','',$item['content']);
				$content = str_replace('</p>','',$contents);
				$resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $item['answer_id']); 
				$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item->feedback->title); 
				$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $content); 
				$i ++; 
			}
			ob_end_clean();
			ob_start();

			header('Content-Type : application/vnd.ms-excel');
			header('Content-Disposition:attachment;filename="'.'产品信息表-'.date("Y年m月j日").'.xls"');
			$objWriter= PHPExcel_IOFactory::createWriter($resultPHPExcel,'Excel5');
			$objWriter->save('php://output');*/


	        $objPHPExcel = PHPExcel_IOFactory::load("../images/aisi-2013-08-16.xlsx");
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
 
			$stockList = array();
			for ($rowNumber = 1; $rowNumber <= $highestRow; $rowNumber++) {
				$stockList[$rowNumber] = array(
						'id' =>trim(
								$sheet->getCellByColumnAndRow(0, $rowNumber)->getValue()),
						'fristname' => trim(
								$sheet->getCellByColumnAndRow(1, $rowNumber)->getValue()),
						'lastname' => trim(
								$sheet->getCellByColumnAndRow(2, $rowNumber)->getValue()),
						'age' => trim(
								$sheet->getCellByColumnAndRow(8, $rowNumber)->getValue()),
						'create_time' => gmdate("Y/m/d", PHPExcel_Shared_Date::ExcelToPHP(trim(
								$sheet->getCellByColumnAndRow(9, $rowNumber)->getValue()))),
				);
			} //CELL

			//print_r();exit();
			$this->render('excel',array('stockList' => $stockList));
			
	}

	public function actionOutput(){
			spl_autoload_unregister(array('YiiBase', 'autoload'));
	        $phpExcelPath = Yii::getPathOfAlias('application.extensions.phpexcel');
			$resultPHPExcel = new PHPExcel();
			$resultPHPExcel->getActiveSheet()->setCellValue('A1', '季度'); 
			$resultPHPExcel->getActiveSheet()->setCellValue('B1', '名称'); 
			$resultPHPExcel->getActiveSheet()->setCellValue('C1', '数量'); 
			// Excel  打开后显示的工作表
			$objPHPExcel->setActiveSheetIndex(0); 

			header('Content-Type: application/vnd.openxmlformats-office document.spreadsheetml.sheet'); 
			header('Content-Disposition: attachment;filename="report.xlsx"'); 
			header('Cache-Control: max-age=0'); 
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007'); 
			$objWriter->save('php://output');
			Yii::app()->end();
			spl_autoload_register(array('YiiBase','autoload')); 

		

 
	}
}

?>