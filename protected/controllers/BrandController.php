<?php 
class BrandController extends Controller{
	public function actionIndex() 
	{
		$this->pageTitle = Yii::t('common', '品牌中心') . SEPARATOR
				. Setting::getValueByCode('inside_title', true);

		$criteria = new CDbCriteria();
		$criteria->compare('t.is_released',1);
		$criteria->order = 'sort_order ASC';
		$brands = Brand::model()->localized()->findAll($criteria);
		
		// 广告图
		$criteria = new CDbCriteria();
		$criteria->compare('t.banner_position_id',2);
		$banner = Banner::model()->localized()->find($criteria);


		$codes = BrandI18n::model()->getFirstCode();
		$this->layout = 'main';

		$regions = Region::model()->getSelects();
		$this->render('index',
			array(
				'brands' => $brands,
				'regions' => $regions,
				'banner' => $banner,
				'codes' => $codes,
				));	
	}

	public function actionView() {
		$previd 	= 0;		// 上一个品牌
		$nextid 	= 0;		// 下一个品牌
		$brand 		= null;
		$prevname	= '';
		$nextname	= '';
		$products 	= null;
		if (isset($_GET['id'])) {
			$id = Yii::app()->request->getQuery('id');
			// 浏览数量加1
			$brandModel = Brand::model()->findByPk($id);
            $brandModel->view_count += 1;
            $brandModel->update();

			$brand = Brand::model()->localized()->findByPk($id);

			$criteria = new CDbCriteria();
			$criteria->compare('t.is_released',1);
			$criteria->compare('t.brand_id',$id);
			$products = Product::model()->localized()->findAll($criteria);

			$criteria = new CDbCriteria();
			$criteria->compare('t.is_released',1);
			$criteria->order = 'sort_order ASC';
			$brands = Brand::model()->localized()->findAll($criteria);
			
			foreach ($brands as $key => $value) {
				if ($value->brand_id == $id && $key != 0 ) {
					$previd = isset($brands[($key-1)])?$brands[($key-1)]['brand_id']:0;
					$nextid = isset($brands[($key+1)])?$brands[($key+1)]['brand_id']:0;

					$prevname = isset($brands[($key-1)])?$brands[($key-1)]['title']:'';
					$nextname = isset($brands[($key+1)])?$brands[($key+1)]['title']:'';

				}else if($value->brand_id == $id && $key == 0 ){
					$nextid = isset($brands[($key+1)])?$brands[($key+1)]['brand_id']:0;
					$nextname = isset($brands[($key+1)])?$brands[($key+1)]['title']:'';
				}
			}
		}

		if (empty($brand)){
			throw new CHttpException(404);
		}

		// 广告图
		$criteria = new CDbCriteria();
		$criteria->compare('t.banner_position_id',2);
		$banner = Banner::model()->localized()->find($criteria);

		$this->pageTitle = Yii::t('common', '品牌中心') . SEPARATOR . Setting::getValueByCode('inside_title', true);

		$this->render('view',
			array(
				'brand'  => $brand,
				'banner' => $banner,
				'previd' => $previd,
				'nextid' => $nextid,
				'prevname' => $prevname,
				'nextname' => $nextname,
				'products' => $products,
			));
	}
}