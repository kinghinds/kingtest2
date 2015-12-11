<?php

class ProductSidebar extends CWidget {
	public function run() {
        $currentMenuId = Yii::app()->controller->productCategoryId;
		$arr[0] = $currentMenuId;
		$currProductCategory = ProductCategory::model()->localized()->findByPk($currentMenuId);
		while ($currProductCategory && $currProductCategory->parent)
		{
			array_push($arr, $currProductCategory->parent->product_category_id);
			$currProductCategory = $currProductCategory->parent;
		}

        foreach(ProductCategory::model()->localized()->findAll(array('condition'=>'parent_id=0','order'=>'sort_order asc')) as $i=>$model) {
			$i ++;
			
			// 是否有下级分类产品
			$hasChildrenProduct = 0;
			$children = $model->getChildren();
			foreach ($children as $child)
			{
				if ($child->is_released == 1)
				{
					$count = Product::model()->localized()->count('product_category_id='.$child->getPrimaryKey());
					if ($count)
					{
						$hasChildrenProduct = 1;
						break;
					}
				}
			}


            $items[$i] = array(
                'label'=>$model->title,
                'url'=>$hasChildrenProduct ? Yii::app()->createUrl('product/detail', array('id' => $model->primaryKey)) : Yii::app()->createUrl('product/series', array('id' => $model->primaryKey)),
                'active'=>$currentMenuId && in_array($model->getPrimaryKey(), $arr),
                'itemOptions'=>null,
            );
			
			

            if($count = ProductCategory::model()->localized()->count('parent_id='.$model->getPrimaryKey())) {
                $items2 = array();
                foreach(ProductCategory::model()->localized()->findAll(array('condition'=>'parent_id='.$model->getPrimaryKey(),'order'=>'sort_order asc')) as $i2=>$model2) {
					
					// 当前目录是否有产品
					$productCount = Product::model()->localized()->count('product_category_id='.$model2->getPrimaryKey());

					// 是否有下级分类产品
					$hasChildrenProduct = 0;
					$children = $model2->getChildren();
					foreach ($children as $child)
					{
						if ($child->is_released == 1)
						{
							$count = Product::model()->localized()->count('product_category_id='.$child->getPrimaryKey());
							if ($count)
							{
								$hasChildrenProduct = 1;
								break;
							}
						}
					}
					if($productCount == 0 && $hasChildrenProduct == 0)
						continue;

                    $items2[$i2] = array(
                        'label'=>$model2->title,
                        'url'=>Yii::app()->createUrl('product/category', array('id' => $model->primaryKey)),
                		'active'=>$currentMenuId && in_array($model2->getPrimaryKey(), $arr),
                		'itemOptions'=>array('class' => 'probar'),
                    );

					if($count = ProductCategory::model()->localized()->count('parent_id='.$model2->getPrimaryKey())) {
						$items3 = array();
						foreach(ProductCategory::model()->localized()->findAll(array('condition'=>'parent_id='.$model2->getPrimaryKey(),'order'=>'sort_order asc')) as $i3=>$model3) {   
							
							// 当前目录是否有产品
							$productCount = Product::model()->localized()->count('product_category_id='.$model3->getPrimaryKey());

							// 是否有下级分类产品
							$hasChildrenProduct = 0;
							$children = $model3->getChildren();
							foreach ($children as $child)
							{
								if ($child->is_released == 1)
								{
									$count = Product::model()->localized()->count('product_category_id='.$child->getPrimaryKey());
									if ($count)
									{
										$hasChildrenProduct = 1;
										break;
									}
								}
							}
							if($productCount == 0 && $hasChildrenProduct == 0)
								continue;

							$items3[$i3] = array(
								'label'=>$model3->title,
								'url'=>Yii::app()->createUrl('product/series', array('id' => $model3->primaryKey)),
								'active'=>$currentMenuId && in_array($model3->getPrimaryKey(), $arr),
								'itemOptions'=>array('class' => 'active'),
							);
							if($count = ProductCategory::model()->localized()->count('parent_id='.$model3->getPrimaryKey())) {
								$items4 = array();
								foreach(ProductCategory::model()->localized()->findAll(array('condition'=>'parent_id='.$model3->getPrimaryKey(),'order'=>'sort_order asc')) as $i4=>$model4) {        			
									$items4[$i4] = array(
										'label'=>$model4->title,
										'url'=>Yii::app()->createUrl('product/series', array('id' => $model4->primaryKey)),
										'active'=>$currentMenuId && in_array($model4->getPrimaryKey(), $arr),
										'itemOptions'=>array('class' => 'active'),
									);
								}
								$items3[$i3]['items'] = $items4;               
							}
						}
						$items2[$i2]['items'] = $items3;   
						
					}
					

                }
                $items[$i]['items'] = $items2;               
            }
        }
		$title = Page::model()->localized()->findByPk(4)->title;
		$this->render('productSidebar', array(
				'title' => $title,
				'items' => $items			
			));
    }
}

?>