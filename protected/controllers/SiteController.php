<?php
header("Content-type:text/html;charset=utf-8");
class SiteController extends Controller {

	public function actionError() {
		$error = Yii::app()->errorHandler->error;
		if ($error) {
			if (Yii::app()->request->isAjaxRequest) {
				echo $error['message'];
			} else {
				$this->pageTitle = 'Error ' . $error['code'] . SEPARATOR
						. Setting::getValueByCode('inside_title', true);
				$template = in_array($error['code'], array(404)) ? 'error_'
								. $error['code'] : 'error';
				$this->render($template, $error);
			}
		} else {
			$this->redirect(array('index'));
		}
	}

	public function actionIndex() {
		// 推荐产品
		$criteria = new CDbCriteria();
		$criteria->compare('t.is_recommend',1);
		$criteria->compare('t.is_released',1);
		$criteria->order = 't.sort_order ASC';
		$products = Product::model()->localized()->findAll($criteria);

		// 推荐品牌
		$criteria = new CDbCriteria();
		$criteria->compare('t.is_recommend',1);
		$criteria->compare('t.is_released',1);
		$criteria->order = 't.sort_order ASC';
		$brands = Brand::model()->localized()->findAll($criteria);

		// 推荐服务
		$criteria = new CDbCriteria();
		$criteria->compare('t.is_recommend',1);
		$criteria->compare('t.is_released',1);
		$criteria->order = 't.sort_order ASC';
		$servers = Server::model()->localized()->findAll($criteria);

		$this->layout = 'main';
		$this->pageTitle = Yii::t('common', '首页') . SEPARATOR . Setting::getValueByCode('inside_title', true);
		$this->metaKeywords = Setting::getValueByCode('meta_keywords', true);
		$this->metaDescription = Setting::getValueByCode('meta_description', true);
		$this->render('index', array(
				'products' => $products,
				'brands' => $brands,
				'servers' => $servers,
		));
		//$this->renderPartial('index');
	}

	// 搜索
	public function actionSearch() {
		$keyword = $_GET['keyword']?trim($_GET['keyword']):"";
		$num = 0;
		$products = null;
		$brands = null;
		
		// 品牌搜索
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('t.title', $keyword, true, 'OR');
		$criteria->addSearchCondition('t.sub_content', $keyword,true, 'OR');
		$criteria->addSearchCondition('t.content', $keyword, true, 'OR');
		if($_GET['lang'] === 'en'){
			$criteria->addSearchCondition('localized.title', $keyword,true, 'OR');
			$criteria->addSearchCondition('localized.sub_content', $keyword,true, 'OR');
			$criteria->addSearchCondition('localized.content', $keyword,true, 'OR');
		}
				
		$brands = Brand::model()->localized()->findAll($criteria);

		// 产品搜索
		$criteria = new CDbCriteria();
		$criteria->select = 't.series_id';
		$criteria->addSearchCondition('t.title', $keyword, true, 'OR');
		$criteria->addSearchCondition('t.norms', $keyword,true, 'OR');
		$criteria->addSearchCondition('t.series_model', $keyword,true, 'OR');
		$criteria->addSearchCondition('t.content', $keyword,true, 'OR');
		if($_GET['lang'] === 'en'){
			$criteria->select = 'localized.owner_id as series_id';
			$criteria->addSearchCondition('localized.title', $keyword, true, 'OR');
			$criteria->addSearchCondition('localized.norms', $keyword,true, 'OR');
			$criteria->addSearchCondition('localized.series_model', $keyword,true, 'OR');
			$criteria->addSearchCondition('localized.content', $keyword,true, 'OR');
		}
		$serieids = ProductSeries::model()->localized()->findAll($criteria);
		$ids = array();
		foreach ($serieids as $key => $value) {
			$ids[] = $value->series_id;
		}

		$criteria = new CDbCriteria();
		if (!empty($ids)) {
			$criteria->addInCondition('t.series_id', $ids);
		}
		
		$criteria->addSearchCondition('t.name', $keyword, true, 'OR');
		if($_GET['lang'] === 'en'){
			$criteria->addSearchCondition('localized.name', $keyword, true, 'OR');
		}
		$products = Product::model()->localized()->findAll($criteria);

		$num = count($products) + count($brands);

		$this->pageTitle = Yii::t('common', '首页') . SEPARATOR
				. Setting::getValueByCode('inside_title', true);
		$this->render('search',
			array(
				'keyword'=> $keyword,
				'brands'=> $brands,
				'products'=> $products,
				'num'=> $num,
			));
	}

}

?>