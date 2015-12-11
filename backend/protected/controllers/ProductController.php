<?php

class ProductController extends Controller {

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
		if (Yii::app()->user->checkAccess('viewProduct') == false) {
			throw new CHttpException(403);
		}

		$pageSize = Yii::app()->request->getQuery('pagesize', 10);
		$keyword = trim(Yii::app()->request->getQuery('keyword'));

		$criteria = new CDbCriteria();
		$criteria->group = "t.product_id";
		if (empty($keyword) == false) {
			if (preg_match('/^#\d+$/', $keyword)) {
				$criteria->compare('product_id', substr($keyword, 1));
			} else {
				$criteria->addSearchCondition('t.name', $keyword, true, 'OR');
			}
		}

		$dataProvider = new CActiveDataProvider(
				Product::model()->multilingual()->together(), array(
						'criteria' => $criteria,
						'sort' => array('defaultOrder' => 't.sort_order ASC'),
						'pagination' => array('pageSize' => $pageSize)
				)
		);
		$this->breadcrumbs = array('产品');

		$this->render('index', array(
				'dataProvider' => $dataProvider,
				'keyword' => $keyword,
		));
	}

	// 创建
	public function actionCreate() {
		if (Yii::app()->user->checkAccess('createProduct') == false) {
			throw new CHttpException(403);
		}

		$product = new Product();
		// $imageList = array();

		if (isset($_POST['Product'])) {
			$product->attributes = Yii::app()->request->getPost('Product');
			$product->productFile = CUploadedFile::getInstance($product, 
					'productFile');			
			foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) {
				$product->i18nFormData['productFile_' . $lang] = CUploadedFile::getInstance($product, 'i18nFormData[productFile_' . $lang . ']');
			}

			$product->category_id = $product->series->category->category_id;
			$product->brand_id = $product->series->brand_id;
			$product->create_time = time();
			$product->update_time = time();
			$product->sort_order = $product->getMaxSortOrder() + 1;
			// $imageList = Yii::app()->request->getPost('imageList');
			if ($product->validate() && $product->save()) {
				// 保存图片数据
				// if (count($imageList) > 0) {
				// 	foreach ($imageList as $productImageId => $imageInfo) {
				// 		$productImage = ProductImage::model()->findByPk($productImageId);
				// 		$productImage->product_id = $product->primaryKey;
				// 		$productImage->file_name = $imageInfo['file_name'];
				// 		$productImage->sort_order = $imageInfo['sort_order'];

				// 		if (isset($imageInfo['is_released'])) {
				// 			$productImage->is_released = $imageInfo['is_released'];
				// 		} else {
				// 			$productImage->is_released = 0;
				// 		}
				// 		$productImage->save(true,
				// 						array('product_id', 'label','file_name','sort_order', 'is_released'));
				// 	}
				// }

				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 产品添加成功', array('{link}' => CHtml::link($product->name, 
							array('view', 'id' => $product->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		}else{
			$product->is_recommend = 1;
		}

		$this->breadcrumbs = array(
				'产品' => array('index'), 
				'添加'
		);
		// $productCategoryOptions = ProductCategory::model()->getLeaveOptions();
		$productSeriesOptions = ProductSeries::model()->getOptions();
		$this->render('create', array(
				'product' => $product,
				// 'imageList'=>$imageList,
				// 'productCategoryOptions'=>$productCategoryOptions,
				'productSeriesOptions' => $productSeriesOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	// 更新
	public function actionUpdate($id) {
		if (Yii::app()->user->checkAccess('updateProduct') == false) {
			throw new CHttpException(403);
		}

		$product = Product::model()->multilingual()->findByPk($id);
		if (is_null($product)) {
			throw new CHttpException(404);
		}
		$imageList = array();

		if (isset($_POST['Product'])) {
			$product->attributes = Yii::app()->request->getPost('Product');
			$product->productFile = CUploadedFile::getInstance($product, 
					'productFile');			
			foreach (I18nHelper::getFrontendLanguages() as $lang => $attr) {
				$product->i18nFormData['productFile_' . $lang] = 
						CUploadedFile::getInstance($product, 
								'i18nFormData[productFile_' . $lang . ']');
			}
			$imageList = Yii::app()->request->getPost('imageList');

			$product->category_id = $product->series->category->category_id;
			$product->brand_id = $product->series->brand_id;
			$product->update_time = time();
			
			if ($product->validate() && $product->save()) {
				// 保存图片数据
				// if (count($imageList) > 0) {
				// 	foreach ($imageList as $productImageId => $imageInfo) {
				// 		$productImage = ProductImage::model()
				// 				->findByPk($productImageId);
				// 		$productImage->sort_order = $imageInfo['sort_order'];
				// 		$productImage->file_name = $imageInfo['file_name'];
				// 		if (isset($imageInfo['is_released'])) {
				// 			$productImage->is_released = $imageInfo['is_released'];
				// 		} else {
				// 			$productImage->is_released = 0;
				// 		}
				// 		$productImage
				// 				->save(true,
				// 						array('label', 'file_name','sort_order',
				// 								'is_released'));
				// 	}
				// }
				$this->setFlashMessage(strtr(
						'<strong>{link}</strong> 产品修改成功', array(
								'{link}' => CHtml::link($product->name, array(
										'view', 'id' => $product->primaryKey
								))
						)
				));
				$this->redirect($this->getReturnUrl());
			}
		}
		else {
			$product->i18nFormData = $product->getI18nAttributes();
			// foreach ($product->images as $productImage) {
			// 	$imageList[$productImage->primaryKey] = array(
			// 			'product_id' => $productImage->product_id,
			// 			'file_name' => $productImage->file_name,
			// 			'thumbnail_image_url' => $productImage
			// 					->getThumbnailImageUrl(),
			// 			'sort_order' => $productImage->sort_order,
			// 			'is_released' => $productImage->is_released);
			// }
		} 

		$this->breadcrumbs = array(
				'产品' => array('index'), 
				'修改'
		);
		// $productCategoryOptions = ProductCategory::model()->getLeaveOptions();
		$productSeriesOptions = ProductSeries::model()->getOptions();
		$this->render('create', array(
				'product' => $product,
				'imageList'=>$imageList,
				'productSeriesOptions'=>$productSeriesOptions,
				// 'productCategoryOptions'=>$productCategoryOptions,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	// 改变发布状态
	public function actionUpdateIsReleased() {
		if (Yii::app()->user->checkAccess('updateProduct') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getPost('id');
		$language = Yii::app()->request->getPost('language');
		$isReleased = Yii::app()->request->getPost('is_released');
		if ($language == I18nHelper::getFrontendSourceLanguage()) {
			echo Product::model()->updateByPk($id, array(
					'is_released' => $isReleased
			));
		} else {
			echo ProductI18n::model()->updateAll(
					array('is_released' => $isReleased),
					'owner_id = :owner_id AND lang = :lang',
					array(':owner_id' => $id, ':lang' => $language)
			);
		}
	}

	// 单个删除
	public function actionDelete() {
		if (Yii::app()->user->checkAccess('deleteProduct') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$cases = Product::model()->multilingual()->findByPk($id);
			if (is_null($cases) == false) {
				$cases->delete();
			}
		}
	}

	// 批量删除
	public function actionBatchDelete() {
		if (Yii::app()->user->checkAccess('deleteProduct') == false) {
			throw new CHttpException(403);
		}
		
		$idList = Yii::app()->request->getPost('product_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('product_id', $idList);
			$casess = Product::model()->findAll($criteria);
			$flag = 0;
			foreach ($casess as $cases) {
				if ($cases->delete()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('产品已成功删除');
			} else {
				$this->setFlashMessage('产品删除失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

	// 单个推荐
	public function actionCommend() {
		
		$id = Yii::app()->request->getQuery('id');
		$product = Product::model()->multilingual()->findByPk($id);
		if (is_null($product) == false) {
			$product->is_recommend = 1;
			$product->save();
			$this->setFlashMessage('产品推荐成功');
		}else{
			$this->setFlashMessage('产品推荐失败', 'warn');
		}
		$this->redirect(array('index'));
	}

	// 批量推荐
	public function actionBatchCommend() {
		
		$idList = Yii::app()->request->getPost('product_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('product_id', $idList);
			$products = Product::model()->findAll($criteria);
			$flag = 0;
			foreach ($products as $product) {
				$product->is_recommend = 1;
				if ($product->save()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('产品推荐成功');
			} else {
				$this->setFlashMessage('产品推荐失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

	// 批量不推荐
	public function actionBatchUnCommend() {

		$idList = Yii::app()->request->getPost('product_id', array());
		if (count($idList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('product_id', $idList);
			$products = Product::model()->findAll($criteria);
			$flag = 0;
			foreach ($products as $product) {
				$product->is_recommend = 0;
				if ($product->save()) {
					$flag++;
				}
			}
			if ($flag > 0) {
				$this->setFlashMessage('产品取消推荐成功');
			} else {
				$this->setFlashMessage('产品取消推荐失败', 'warn');
			}
		} else {
			$this->setFlashMessage('没有记录被选中', 'warn');
		}
		$this->redirect(array('index'));
	}

	// 置顶
	public function actionSortFirst() {
		if (Yii::app()->user->checkAccess('updateProduct') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$banner = Product::model()->findByPk($id);
		if (empty($banner) == false) {
			$sortOrder = $banner->getMinSortOrder();
			if ($sortOrder < 2) {
				$banner->updateAll(array(
					'sort_order' => new CDbExpression('sort_order + 1')
				));
			}
			$banner->updateByPk($banner->primaryKey, array(
				'sort_order' => ($sortOrder - 1)
			));
		}
		$this->redirect(array('index'));
	}

	// 排序上一位
	public function actionSortPrevious() {
		if (Yii::app()->user->checkAccess('updateProduct') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$banner = Product::model()->findByPk($id);
		if (empty($banner) == false) {
			$sortOrder = $banner->getPreviousSortOrder();
			if ($sortOrder > 0) {
				$banner->updateAll(
						array('sort_order' => $banner->sort_order),
						'sort_order = :sort_order',
						array('sort_order' => $sortOrder)
				);
				$banner->updateByPk($banner->primaryKey, array(
						'sort_order' => $sortOrder
				));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortNext() {
		if (Yii::app()->user->checkAccess('updateProduct') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$banner = Product::model()->findByPk($id);
		if (empty($banner) == false) {
			$sortOrder = $banner->getNextSortOrder();
			if ($sortOrder > 0) {
				$banner->updateAll(
						array('sort_order' => $banner->sort_order),
						'sort_order = :sort_order',
						array('sort_order' => $sortOrder)
				);
				$banner->updateByPk($banner->primaryKey, array(
						'sort_order' => $sortOrder
				));
			}
		}
		$this->redirect(array('index'));
	}

	public function actionSortLast() {
		if (Yii::app()->user->checkAccess('updateProduct') == false) {
			throw new CHttpException(403);
		}

		$id = Yii::app()->request->getQuery('id');
		$banner = Product::model()->findByPk($id);
		if (empty($banner) == false) {
			$banner->updateByPk($banner->primaryKey, array(
					'sort_order' => ($banner->getMaxSortOrder() + 1)
			));
		}
		$this->redirect(array('index'));
	}

	public function actionSortSpecify() {
		if (Yii::app()->user->checkAccess('updateProduct') == false) {
			throw new CHttpException(403);
		}
		
		if (isset($_POST['target_id']) && isset($_POST['pos'])) {
			$id = Yii::app()->request->getQuery('id');
			$targetId = Yii::app()->request->getPost('target_id');
			$pos = Yii::app()->request->getPost('pos', '1');

			$baseModel = Product::model()->findByPk($id);
			if (empty($baseModel)) {
				echo CJSON::encode(array(
						'result' => false, 
						'message' => '操作对象不存在'
				));
				Yii::app()->end();
			}

			$targetModel = Product::model()->findByPk($targetId);
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
		if (Yii::app()->user->checkAccess('updateProduct') == false) {
			throw new CHttpException(403);
		}

		if (Yii::app()->request->isAJAXRequest) {
			$id = Yii::app()->request->getQuery('id');
			$sortOrder = Yii::app()->request->getQuery('sort_order');
			$banner = Product::model()->multilingual()->findByPk($id);
			if (is_null($banner) == false) {
				$banner->sort_order = $sortOrder;
				$banner->save();
			}
		}
	}

	public function actionUploadImage() {

		try {
			$productId = intval(Yii::app()->request->getPost('product_id'));

			$file = CUploadedFile::getInstanceByName('Filedata');
			// 检查上传文件
			if ($file instanceof CUploadedFile == false) {
				throw new Exception('无法识别上传文件');
			}

			// 检查尺寸
			list($width, $height, $type, $attr) = getimagesize($file->tempName);
			if (empty($width) || empty($height)) {
				throw new Exception($file->name . ' 无法识别图片');
			}
			if ($width < 380) {
				throw new Exception(
						$file->name . ' 尺寸不符合要求，请上传宽大于或等于 380 像素的图片');
			}

			// 保存原图
			$fileName = md5($file->tempName . uniqid()) . '.'
					. $file->extensionName;
			$filePath = Helper::mediaPath(
					Product::UPLOAD_ORIGINAL_IMAGE_PATH . $fileName, FRONTEND);
			$file->saveAs($filePath, false);

			// 裁切大图
			require_once('Image.php');
			$image = new Image($filePath);
			$image->resize(380, 380)
					->save(
							Helper::mediaPath(
									Product::UPLOAD_LARGE_IMAGE_PATH
											. $fileName, FRONTEND));

			// 裁切缩略图
			$image = new Image($filePath);
			$image->resize(80, 80)
					->save(
							Helper::mediaPath(
									Product::UPLOAD_THUMBNAIL_IMAGE_PATH
											. $fileName, FRONTEND));

			// 入库
			$model = new ProductImage();
			$model->product_id = $productId;
			$model->file_name = $file->name;
			$model->image_path = $fileName;
			$model->sort_order = 0;
			$model->is_released = 1;
			$model->save();

			echo CJSON::encode(
					array('result' => true,
							'product_image_id' => $model->primaryKey,
							'product_id' => $productId,
							'thumbnail_image_url' => $model
									->getThumbnailImageUrl(),
							'file_name' => $model->file_name));
		} catch (Exception $e) {
			echo CJSON::encode(
					array('result' => false, 'message' => $e->getMessage()));
		}
	}

	public function actionDeleteImage() {
		$productImageId = Yii::app()->request->getQuery('product_image_id');
		$model = ProductImage::model()->findByPk($productImageId);
		if (empty($model) == false && $model->delete())
			echo 1;
		else
			echo 0;
	}
}

?>