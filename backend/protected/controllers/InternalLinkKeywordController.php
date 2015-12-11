<?php

class InternalLinkKeywordController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewInternalLinkKeyword') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getQuery('keyword'));
		$lang = Yii::app()->request->getQuery('lang');
		if (empty($lang)) {
			$lang = I18nHelper::getFrontendSourceLanguage();
		}

		$criteria = new CDbCriteria();
		$criteria->compare('lang', $lang);
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('internal_link_keyword_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.keyword', $keyword);
			}
		}

		$dataProvider = new CActiveDataProvider(
				InternalLinkKeyword::model(), array(
						'criteria' => $criteria,
						'sort' => array(
								'defaultOrder' => 't.keyword ASC, t.internal_link_keyword_id ASC'
						),
						'pagination' => array('pageSize' => $pageSize)
				)
		);

		$langOptions = array();
		foreach (I18nHelper::getFrontendLanguages(false) as $curLang => $attr) {
			$langOptions[$curLang] = $attr['label'];
		}

		$this->breadcrumbs = array('内链关键词');

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
				'lang' => $lang,
				'langOptions' => $langOptions
		));
	}

	public function actionView($id) {
		if (Yii::app()->user->checkAccess('viewInternalLinkKeyword') == false) {
			throw new CHttpException(403);
		}

		$internalLinkKeyword = InternalLinkKeyword::model()->findByPk($id);
		if (is_null($internalLinkKeyword)) {
			throw new CHttpException(404);
		}

		$relatedList = $internalLinkKeyword->getRelatedList();

		$this->breadcrumbs = array(
				'内链关键词' => array('index'), 
				'查看'
		);

		$this->render('view', array(
				'internalLinkKeyword' => $internalLinkKeyword,
				'relatedList' => $relatedList
		));
	}

	public function actionCheck() {
		if (Yii::app()->request->isAjaxRequest) {
			$relatedType = Yii::app()->request->getPost('related_type');
			$relatedId = Yii::app()->request->getPost('related_id');
			$lang = Yii::app()->request->getPost('lang');
			$keywords = Yii::app()->request->getPost('keywords');
			$keywordList = InternalLinkKeyword::getExistedKeywords(
					$relatedType, $relatedId, $lang, $keywords);
			if (count($keywordList) > 0) {
				echo CJSON::encode(array(
						'result' => false,
						'message' => strtr('关键词 {keywords} 已存在', array(
								'{keywords}' => implode(', ', $keywordList)
						))
				));
			} else {
				echo CJSON::encode(array(
						'result' => true
				));
			}
			Yii::app()->end();
		}
	}
}

?>