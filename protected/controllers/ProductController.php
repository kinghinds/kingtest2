<?php 
header("Content-type:text/html;charset=utf-8");
class ProductController extends Controller{
	public function actionIndex() 
	{
		$brand_id = isset($_GET['brand_id'])?$_GET['brand_id']:0;

		$productCategoryOptions = ProductCategory::model()->byBrandOptions($brand_id);

		// 产品
		$criteria = new CDbCriteria();
		$criteria->compare('t.is_released',1);
		$productarr = Product::model()->localized()->findAll($criteria);


		$brand = array();
		if ($brand_id != 0) {
			$criteria = new CDbCriteria();
			$criteria->compare('t.brand_id',$brand_id);
			$criteria->order = 'sort_order ASC';
			$brand = Brand::model()->localized()->find($criteria);
			$productCategoryOptions = ProductCategory::model()->byBrandOptions($brand_id);

			// 产品
		$criteria = new CDbCriteria();
		$criteria->compare('t.brand_id',$brand_id);
		$productarr = Product::model()->localized()->findAll($criteria);
		}

		$products=array();
		foreach($productarr as $model){
			$products[]=$model->attributes;
		}
		

		// 广告图
		$criteria = new CDbCriteria();
		$criteria->compare('t.banner_position_id',1);
		$banner = Banner::model()->localized()->find($criteria);


		$brands = array();
		$criteria = new CDbCriteria();
		$criteria->select = 'brand_id,title';
		$criteria->compare('t.is_released',1);
		$brandsarr = Brand::model()->localized()->findAll($criteria);
		foreach($brandsarr as $model){
			$brands[$model->brand_id]=$model->title;
		}

		$series = array();
		$criteria = new CDbCriteria();
		$criteria->select = 'series_id,title';
		$criteria->compare('t.is_released',1);
		$seriesarr = ProductSeries::model()->localized()->findAll($criteria);
		foreach($seriesarr as $model){
			$series[$model->series_id]=$model->title;
		}

		$categorys = array();
		$criteria = new CDbCriteria();
		$criteria->select = 'category_id,name';
		$criteria->compare('t.is_released',1);
		$categorysarr = ProductCategory::model()->localized()->findAll($criteria);
		foreach($categorysarr as $model){
			$categorys[$model->category_id]=$model->name;
		}

		$this->layout = 'main';
		$this->pageTitle = Yii::t('common', '产品中心') . SEPARATOR
				. Setting::getValueByCode('inside_title', true);

		$this->render('index',
			array(
				'brand_id'=>$brand_id,
				'brand' => $brand,
				'products' => $products,
				'brands' => $brands,
				'series' => $series,
				'categorys' => $categorys,
				'productCategoryOptions' => $productCategoryOptions,
				'banner' => $banner
				));	
	}

	public function actionView() {
		$previd 	= 0;		// 上一个品牌
		$nextid 	= 0;		// 下一个品牌
		$serie 		= null;
		$prevname	= '';
		$nextname	= '';
		$products 	= null;
		if (isset($_GET['seriesid'])) {
			$id = Yii::app()->request->getQuery('seriesid');

			$serie = ProductSeries::model()->localized()->findByPk($id);

			$criteria = new CDbCriteria();
			$criteria->compare('t.is_released',1);
			$criteria->compare('t.series_id',$id);
			$products = Product::model()->localized()->findAll($criteria);

			$criteria = new CDbCriteria();
			$criteria->compare('t.is_released',1);
			$series = ProductSeries::model()->localized()->findAll($criteria);
			
			foreach ($series as $key => $value) {
				if ($value->series_id == $id && $key != 0 ) {
					$previd = isset($series[($key-1)])?$series[($key-1)]['series_id']:0;
					$nextid = isset($series[($key+1)])?$series[($key+1)]['series_id']:0;

					$prevname = isset($series[($key-1)])?$series[($key-1)]['title']:'';
					$nextname = isset($series[($key+1)])?$series[($key+1)]['title']:'';

				}else if($value->series_id == $id && $key == 0 ){
					$nextid = isset($series[($key+1)])?$series[($key+1)]['series_id']:0;
					$nextname = isset($series[($key+1)])?$series[($key+1)]['title']:'';
				}
			}
		}

		if (empty($serie)){
			throw new CHttpException(404);
		}

		// 广告图
		$criteria = new CDbCriteria();
		$criteria->compare('t.banner_position_id',1);
		$banner = Banner::model()->localized()->find($criteria);

		$this->pageTitle = Yii::t('common', '产品中心') . SEPARATOR . Setting::getValueByCode('inside_title', true);

		$this->render('view',
			array(
				'serie'  => $serie,
				'banner' => $banner,
				'previd' => $previd,
				'nextid' => $nextid,
				'prevname' => $prevname,
				'nextname' => $nextname,
				'products' => $products,
			));
	}
}