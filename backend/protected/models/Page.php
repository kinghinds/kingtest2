<?php

class Page extends I18nActiveRecord {
	const UPLOAD_BANNER_PATH = 'upload/banner/';
	const UPLOAD_BG_IMAGE_PATH = 'upload/bg/';

	public $moduleName;
	public $bannerFile;
	public $layer;

	public $bgImageFile;
	public $deleteBgImageFile = false;

	public function i18nAttributes() {
		return array(
				'title', 
				'content',
				'banner_section', 
				'bannerFile',
				'link_url',
				'target_window',
				'internal_link_keywords',
				'is_undisplay_ilk',
				'search_keywords',
				'head_title',
				'meta_description',
				'meta_keywords', 
				'is_released',
				'moduleName'
		);
	}

	public static $moduleSet = array(
			'page' => array('页面', 'isCategory' => false, 'templates' => array(
					'' => '默认',
					'contact' => '联系我们',
			)),
			'link' => array('链接', 'isCategory' => false),
			'sitemap' => array('网站地图', 'isCategory' => false)
	);

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{page}}';
	}

	public function attributeLabels() {
		return array(
				'page_id' => '#编号', 
				'parent_id' => '父级分类',
				'title' => '标题', 
				'slug' => '网址优化', 
				'module_template' => '模板',
				'link_url' => '链接', 
				'sort_order' => '排序',
				'module_name' => '模块', 
				'module_menu' => '菜单',
				'internal_link_keywords' => '内链关键词',
				'is_undisplay_ilk' => '本页面不显示内链关键词链接',
				'search_keywords' => '搜索关键词',
				'head_title' => '页面标题', 
				'meta_description' => '页面描述',
				'meta_keywords' => '页面关键词', 
				'content' => '页面内容',
				'target_window' => '目标窗口',
				'is_indexable' => '索引', 
				'is_released' => '发布',
				'bg_image_path' => '背景图片'
		);
	}

	public function rules() {
		return array(
				array('title, internal_link_keywords, search_keywords, '
						. 'page_title, meta_description, meta_keywords, content, '
						. 'banner_section, module_name, module_menu, '
						. 'module_template,head_title, slug', 'safe'),
				array('parent_id', 'type', 'type' => 'integer'),
				array('parent_id', 'validateParentId'),
				array('is_undisplay_ilk, is_indexable, is_released', 'type', 'type' => 'boolean'),
				array('sort_order', 'type', 'type' => 'integer'),
				array('link_url', 'type', 'type' => 'url'),
				array('target_window', 'safe'),
				array('internal_link_keywords', 'validateInternalLinkKeywords'),
				array('bannerFile', 'file', 'types' => 'swf, jpg, jpeg, gif, png', 
						'allowEmpty' => true),
				array('bannerFile', 'validateBannerFile'),
				array('bgImageFile', 'file', 'types' => 'jpg, jpeg, gif, png', 
						'allowEmpty' => true),
				array('bgImageFile', 'validateImageFile'),
				array('deleteBgImageFile', 'type', 'type' => 'boolean'),
				array('i18nFormData', 'type', 'type' => 'array')
		);
	}

	public function relations() {
		return array(
				'parent' => array(self::BELONGS_TO, 'Page', 'parent_id'),
				'childrenCount' => array(self::STAT, 'Page', 'parent_id')
		);
	}

	public function validateInternalLinkKeywords() {
		if ($this->hasErrors('internal_link_keywords') == false) {
			if (empty($this->internal_link_keywords) == false) {
				foreach (I18nHelper::getFrontendLanguages(false) as $lang => $attr) {
					if ($lang == I18nHelper::getFrontendSourceLanguage()) {
						$existedKeywordList = InternalLinkKeyword::getExistedKeywords(
								'page', 
								($this->isNewRecord ? false : $this->page_id),
								$lang,
								$this->internal_link_keywords
						);
						if (count($existedKeywordList) > 0) {
							$this->addError('internal_link_keywords', strtr(
									'内链关键词 {keywords} 已存在', array(
											'{lang}' => $attr['label'],
											'{keywords}' => CHtml::encode(implode(',', $existedKeywordList))
									)
							));
						}
					} else {	
						$existedKeywordList = InternalLinkKeyword::getExistedKeywords(
								'page', 
								($this->isNewRecord ? false : $this->page_id),
								$lang,
								$this->i18nFormData['internal_link_keywords_' . $lang]
						);
						if (count($existedKeywordList) > 0) {
							$this->addError('internal_link_keywords', strtr(
									'[{lang}] 内链关键词 {keywords} 已存在', array(
											'{lang}' => $attr['label'],
											'{keywords}' => CHtml::encode(implode(',', $existedKeywordList))
									)
							));
						}
					}
				}
			}
		}
	}

	public function validateBannerFile() {
		if ($this->hasErrors('bannerFile') == false
				&& $this->bannerFile instanceof CUploadedFile) {
			if (strtolower($this->bannerFile->extensionName) != 'swf') {
				list($width, $height, $type, $attr) = getimagesize(
						$this->bannerFile->tempName);
				if (empty($width) || empty($height)) {
					$this->addError('bannerFile', $this->bannerFile->name . ' 图片无法识别');
				}
			}
		}
	}

	public function validateImageFile($attribute, $params) {
		if ($this->hasErrors($attribute) == false 
				&& $this->$attribute instanceof CUploadedFile) {
			list($width, $height, $type, $attr) = getimagesize(
					$this->$attribute->tempName);
			if (empty($width) || empty($height)) {
				$this->addError($attribute, 
					$this->$attribute->name . ' 图片无法识别');
			}
		}
	}

	public function validateParentId() {
		if ($this->hasErrors('parent_id') == false) {
			if ($this->isNewRecord == false) {
				if ($this->parent_id == $this->primaryKey) {
					$this->addError('parent_id', '父级分类不能够指定为自己');
				}
			}
		}
	}

	public function beforeSave() {
		foreach (I18nHelper::getFrontendLanguageKeys(false) as $lang) {
			$this->i18nFormData['moduleName_' . $lang] = $this->module_name;
		}

		// 保存 Banner
		if ($this->bannerFile instanceof CUploadedFile
				&& $this->hasErrors('bannerFile') == false) {
			// 保存原文件
			$file = $this->bannerFile;
			$fileName = md5($file->tempName . uniqid()) . '.' . $file->extensionName;
			list($width, $height, $type, $attr) = getimagesize($file->tempName);
			$htmlOptions = array('width' => $width, 'height' => $height);
			$file->saveAs(Helper::mediaPath(self::UPLOAD_BANNER_PATH . $fileName, FRONTEND));

			if ($this->module_name == 'product') {
				$htmlOptions['class'] = 'banner';
			}

			// 生成 HTML 代码
			if (strtolower($file->extensionName) == 'swf') {
				$this->banner_section = Helper::renderFlashHtml(
					Helper::mediaUrl(self::UPLOAD_BANNER_PATH . $fileName, FRONTEND), 
					$htmlOptions
				);
			} else {
				$this->banner_section = CHtml::image(
					Helper::mediaUrl(self::UPLOAD_BANNER_PATH . $fileName, FRONTEND), 
					'', 
					$htmlOptions
				);
			}
		}

		return parent::beforeSave();
	}

	protected function afterSave() {
		parent::afterSave();
		require_once('Image.php');

		// 保存背景图片
		if ($this->bgImageFile instanceof CUploadedFile
				&& $this->hasErrors('bgImageFile') == false) {
			// 保存原文件
			$file = $this->bgImageFile;
			$fileName = md5($file->tempName . uniqid()) . '.' 
					. $file->extensionName;
			$filePath = Helper::mediaPath(
					self::UPLOAD_BG_IMAGE_PATH . $fileName, FRONTEND);
			$file->saveAs($filePath);

			// 更新数据
			$this->updateByPk($this->primaryKey, array(
				'bg_image_path' => $fileName
			));

		} else if ($this->deleteBgImageFile) {
			// 删除图片
			@unlink(Helper::mediaPath(self::UPLOAD_BG_IMAGE_PATH
					. $this->bg_image_path, FRONTEND));

			// 更新数据
			$this->updateByPk($this->primaryKey, array('bg_image_path' => ''));
		}

		// 内链关键词
		foreach (I18nHelper::getFrontendLanguages(false) as $lang => $attr) {
			if ($lang == I18nHelper::getFrontendSourceLanguage()) {
				InternalLinkKeyword::model()->insertOrUpdate('page', 
						$this->page_id, $lang, 
						$this->internal_link_keywords);
			} else {
				InternalLinkKeyword::model()->insertOrUpdate('page', 
						$this->page_id, $lang, 
						$this->i18nFormData['internal_link_keywords_' . $lang]);
			}
		}		
	}

	protected function afterDelete() {
		// 删除内链关键词
		InternalLinkKeyword::model()->deleteAllByAttributes(array(
				'related_type' => 'page',
				'related_id' => $this->page_id
		));

		parent::afterDelete();		
	}

	public function getPermalink() {
		switch ($this->module_name) {
		case 'page':
			if (empty($this->slug) === false && strlen(trim($this->slug)) > 0) {
				return Yii::app()->createUrl('page/view', array('slug' => $this->slug));
			} else {
				return Yii::app()->createUrl('page/view', array('id' => $this->primaryKey));
			}
			break;

		case 'link':
		case 'business':
			if (preg_match('#^(http|https|ftp)://.+$#', $this->link_url)) {
				return $this->link_url;
			} else if (strpos($this->link_url, '/') === 0) {
				//return Yii::app()->baseUrl . '/' . Yii::app()->language . $this->link_url;
				return Yii::app()->baseUrl . $this->link_url;
			} else {
				//return Yii::app()->baseUrl . '/' . Yii::app()->language . '/' . $this->link_url;
				return Yii::app()->baseUrl . '/' . $this->link_url;
			}
			break;

		case 'life':
			if (empty($this->slug) === false && strlen(trim($this->slug)) > 0) {
				return Yii::app()->createUrl('page/life', array('slug' => $this->slug));
			} else {
				return Yii::app()->createUrl('page/life', array('id' => $this->primaryKey));
			}
			break;
		case 'event':
		case 'feedback':
		case 'product':
		case 'faq':
			if (empty($this->slug) === false && strlen(trim($this->slug)) > 0) {
				return Yii::app()->createUrl('lzschool/index');
			}
			break;
		case 'partner':
			if (empty($this->slug) === false && strlen(trim($this->slug)) > 0) {
				return Yii::app()->createUrl('page/join', array('slug' => $this->slug));
			}
		case 'job':
		case 'campusRecruitment':		
			return Yii::app()->createUrl($this->module_name . '/index');
			break;

		case 'news':
			if (empty($this->slug) === false && strlen(trim($this->slug)) > 0) {
				return Yii::app()->createUrl($this->module_name . '/index', array('slug' => $this->slug));
			} else {
				return Yii::app()->createUrl($this->module_name . '/index', array('id' => $this->primaryKey));
			}
			break;

		case 'sitemap':
			return Yii::app()->createUrl('site/' . $this->module_name);
			break;

		default:
		//throw new Exception('"'.$this->module_name.'" is unknown module');
			return null;
		}
	}

	public function getTree() {
		$criteria = new CDbCriteria();
		$criteria->order = 'sort_order ASC';
		$models = self::model()->findAll($criteria);
		return self::getChildrens($models, 0);
	}

	protected function getChildrens($models, $parentId, $layer = 0) {
		$arr = array();
		foreach ($models as $model) {
			if ($model->parent_id == $parentId) {
				$model->layer = $layer;
				array_push($arr, $model);
				$layer++;
				$arr = array_merge($arr,
						self::getChildrens($models, $model->page_id, $layer));
				$layer--;
			}
		}
		return $arr;
	}

	public function getOptions($parentId = 0) {
		static $level = 0;
		$items = array();
		$criteria = new CDbCriteria();
		$criteria->compare('parent_id', $parentId);
		$criteria->order = 'sort_order ASC';
		$models = self::model()->localized(null, false)->findAll($criteria);
		foreach ($models as $model) {
			$items[$model->primaryKey] = str_repeat('--', $level)
					. $model->title;
			if ($model->childrenCount > 0) {
				$level++;
				$items += self::getOptions($model->primaryKey);
				$level--;
			}
		}
		return $items;
	}

	public function getRootOptions() {
		$criteria = new CDbCriteria();
		$criteria->compare('parent_id', 0);
		$criteria->compare('is_indexable', true);
		$criteria->order = 'sort_order ASC';
		$models = self::model()->localized(null, false)->findAll($criteria);
		return CHtml::listData($models, 'page_id', 'title');
	}

	public function getModuleOptions() {
		$arr = array();
		foreach (self::$moduleSet as $module => $prop) {
			$arr[$module] = $prop[0];
		}
		return $arr;
	}

	public function getOptionsByModule($moduleName) {
		$criteria = new CDbCriteria();
		$criteria->compare('module_name', $moduleName);
		$criteria->order = 'sort_order ASC';
		return CHtml::listData(
			self::model()->localized(null, false)->findAll($criteria),
			'primaryKey', 
			'title'
		);
		//return CHtml::listData(self::model()->findAll($criteria), 'primaryKey', 'title');
	}

	public function getRootIdByPk($pageId) {
		$rows = CHtml::listData(self::model()->findAll(), 'page_id',
				'parent_id');
		if (isset($rows[$pageId])) {
			$parentId = $rows[$pageId];
			while (isset($rows[$parentId]) && $rows[$parentId] > 0) {
				$parentId = $rows[$parentId];
			}
			return $parentId;
		} else {
			return false;
		}
	}

	public static function getModuleTemplateOptions($module) {
		$arr = array();
		foreach (Page::$moduleSet[$module]['templates'] as $key => $prop) {
			$arr[$key] = $prop;
		}
		return $arr;
	}

	public function getDisplayModuleName($module_name, $module_template) {
		$str = '';
		if (isset(self::$moduleSet[$module_name][0])) {
			$str = self::$moduleSet[$module_name][0];
			if (isset(self::$moduleSet[$module_name]['templates'])) {
				$template = self::$moduleSet[$module_name]['templates'][$module_template];
				if (isset($template) && empty($template) == false) {
					$str .= ' (' . $template . ')';
				}
			}
		}
		return $str;
	}

	public function getTargetWindowOptions() {
		return array(
			'' => '本窗口打开',
			'_blank' => '新窗口打开'
		);
	}

	public function getTargetWindowLabel($code = false) {
		if ($code == false) {
			$code = $this->target_window;
		}
		$options = $this->getTargetWindowOptions();
		return isset($options[$code]) ? $options[$code] : false;
	}

	public function getMinSortOrder() {
		return (int) Yii::app()->db->createCommand()->select('MIN(sort_order)')
				->from($this->tableName())->queryScalar();
	}

	public function getPreviousSortOrder() {
		return (int) Yii::app()->db->createCommand()->select('sort_order')
				->from($this->tableName())
				->where('parent_id = :parent_id AND sort_order < :sort_order')
				->order('sort_order DESC')
				->bindValues(array(
						':parent_id' => $this->parent_id,
						':sort_order' => $this->sort_order))
				->queryScalar();
	}

	public function getNextSortOrder() {
		return (int) Yii::app()->db->createCommand()->select('sort_order')
				->from($this->tableName())
				->where('parent_id = :parent_id AND sort_order > :sort_order')
				->order('sort_order ASC')
				->bindValues(array(
						':parent_id' => $this->parent_id,
						':sort_order' => $this->sort_order))
				->queryScalar();
	}

	public function getMaxSortOrder() {
		return (int) Yii::app()->db->createCommand()->select('MAX(sort_order)')
				->from($this->tableName())->queryScalar();
	}

	public function getParentBrandcrumbLinks() {
		return self::getBrandcrumbLinks($this->parent_id);
	}

	public static function getBrandcrumbLinks($pageId, $pages = NULL) {
		if (is_null($pages)) {
			$pages = self::model()->localized()->findAll();
		}
		$links = array();
		self::getParentLink($pageId, $pages, $links);
		return array_reverse($links);
	}


	protected static function getParentLink($pageId, $pages, &$links) {
		foreach ($pages as $page) {
			if ($page->page_id == $pageId) {
				$links[$page->title] = $page->getPermalink();
				self::getParentLink($page->parent_id, $pages, $links);
			}
		}
	}

	public function getBgImageUrl() {
		$bgImagePath = Helper::mediaPath(self::UPLOAD_BG_IMAGE_PATH
				. $this->bg_image_path, FRONTEND);	
		if (is_file($bgImagePath)) {
			return Helper::mediaUrl(self::UPLOAD_BG_IMAGE_PATH
					. $this->bg_image_path, FRONTEND);
		} else {
			return false;
		}
	}
}

?>