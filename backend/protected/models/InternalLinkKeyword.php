<?php

class InternalLinkKeyword extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{internal_link_keyword}}';
	}

	public function attributeLabels() {
		return array(
				'internal_link_keyword_id' => '#编号', 
				'related_type' => '相关数据类型',
				'related_id' => '相关数据ID',
				'lang' => '语言',
				'keyword' => '关键词'
		);
	}

	public function rules() {
		return array(
				array('related_type, related_id, lang, keyword', 'required'),
				array('related_type', 'in', 'range' => array(
						'brand_culture',
						'news',
						'dialogue',
						'magazine_article',
						'page',
						'home'
				)),
				array('related_id', 'type', 'type' => 'integer'),
				array('related_id', 'validateRelatedId'),
				array('lang', 'in', 'range' => array('en', 'zh_cn')),
				array('keyword', 'validateKeyword')
		);
	}

	public function validateRelatedId() {
		if ($this->hasErrors('related_type') == false 
				&& $this->hasErrors('related_id') == false) {
			switch ($this->related_type) {
				case 'brand_culture':
					$criteria = new CDbCriteria();
					$criteria->compare('brand_culture_id', $this->related_id);
					$isExist = BrandCulture::model()->exists($criteria);
					if ($isExist == false) {
						$this->addError('related_id', '相关数据不存在或已被删除');
					}
					break;

				case 'news':
					$criteria = new CDbCriteria();
					$criteria->compare('news_id', $this->related_id);
					$isExist = News::model()->exists($criteria);
					if ($isExist == false) {
						$this->addError('related_id', '相关数据不存在或已被删除');
					}
					break;

				case 'dialogue':
					$criteria = new CDbCriteria();
					$criteria->compare('dialogue_id', $this->related_id);
					$isExist = Dialogue::model()->exists($criteria);
					if ($isExist == false) {
						$this->addError('related_id', '相关数据不存在或已被删除');
					}
					break;

				case 'magazine_article':
					$criteria = new CDbCriteria();
					$criteria->compare('magazine_article_id', $this->related_id);
					$isExist = MagazineArticle::model()->exists($criteria);
					if ($isExist == false) {
						$this->addError('related_id', '相关数据不存在或已被删除');
					}
					break;

				case 'page':
					$criteria = new CDbCriteria();
					$criteria->compare('page_id', $this->related_id);
					$isExist = Page::model()->exists($criteria);
					if ($isExist == false) {
						$this->addError('related_id', '相关数据不存在或已被删除');
					}
					break;

				case 'home':
					break;
				
				default:
					$this->addError('related_id', '未知的数据类型');
					break;
			}
		}
	}

	public function validateKeyword() {
		if ($this->hasErrors('keyword') == false) {

		}
	}

	public function insertOrUpdate($relatedType, $relatedId, $lang, $currentKeywords) {
		$currentKeywordList = self::convert2Array($currentKeywords);
		$criteria = new CDbCriteria();
		$criteria->compare('related_type', $relatedType);
		$criteria->compare('related_id', $relatedId);
		$criteria->compare('lang', $lang);
		$keywords = self::model()->findAll($criteria);
		$oldKeywordList = CHtml::listData($keywords, 'internal_link_keyword_id', 
				'keyword');

		$flag = 0;

		// 需要删除的
		$deleteKeywordList = array_diff($oldKeywordList, $currentKeywordList); 
		$criteria = new CDbCriteria();
		$criteria->compare('related_type', $relatedType);
		$criteria->compare('related_id', $relatedId);
		$criteria->compare('lang', $lang);
		$criteria->addInCondition('keyword', $deleteKeywordList);
		$flag += self::model()->deleteAll($criteria);

		// 需要添加的
		$addKeywordsList = array_diff($currentKeywordList, $oldKeywordList); 
		foreach ($addKeywordsList as $addKeyword) {
			$keyword = new self();
			$keyword->related_type = $relatedType;
			$keyword->related_id = $relatedId;
			$keyword->lang = $lang;
			$keyword->keyword = $addKeyword;
			if ($keyword->save()) {
				$flag++;
			}
		}

		return $flag;
	}

	public static function getExistedKeywords($relatedType, $relatedId, $lang, $keywords) {
		$keywordList = self::convert2Array($keywords);
		$existedKeywordList = array();
		if (count($keywordList) > 0) {
			$criteria = new CDbCriteria();
			$criteria->compare('lang', $lang);
			$criteria->addInCondition('keyword', $keywordList);
			$keywords = self::model()->findAll($criteria);
			if (count($keywords) > 0) {
				foreach ($keywords as $curKeyword) {
					foreach ($keywordList as $key => $keyword) {
						if ($curKeyword->keyword == $keyword) {
							if ($curKeyword->related_type != $relatedType 
									|| $curKeyword->related_id != $relatedId) {
								$existedKeywordList[] = CHtml::encode($keyword);
							}
						}
					}						
				}					
			}
		} 

		return $existedKeywordList;
	}

	public function getRelatedCount() {
		if (Yii::app()->language == Yii::app()->sourceLanguage) {
			$aliasPrefix = 't.';
		} else {
			$aliasPrefix = 'localized.';
		}
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition($aliasPrefix . 'content', $this->keyword);
		$brandCultureCount = BrandCulture::model()->localized(
				$this->lang)->count($criteria);

		$criteria = new CDbCriteria();
		$criteria->addSearchCondition($aliasPrefix . 'content', $this->keyword);
		$newsCount = News::model()->localized($this->lang)->count($criteria);

		$criteria = new CDbCriteria();
		$criteria->addSearchCondition($aliasPrefix . 'content', $this->keyword);
		$dialogueCount = Dialogue::model()->localized($this->lang)->count($criteria);

		$criteria = new CDbCriteria();
		$criteria->addSearchCondition($aliasPrefix . 'content', $this->keyword);
		$magazineArticleCount = MagazineArticle::model()->localized(
				$this->lang)->count($criteria);

		$criteria = new CDbCriteria();
		$criteria->addSearchCondition($aliasPrefix . 'content', $this->keyword);
		$pageCount = Page::model()->localized($this->lang)->count($criteria);

		return $brandCultureCount + $newsCount + $dialogueCount
				 + $magazineArticleCount + $pageCount;
	}

	public function getRelatedList() {
		if (Yii::app()->language == Yii::app()->sourceLanguage) {
			$aliasPrefix = 't.';
		} else {
			$aliasPrefix = 'localized.';
		}

		$data = array();

		$criteria = new CDbCriteria();
		$criteria->addSearchCondition($aliasPrefix . 'content', $this->keyword);
		$brandCultures = BrandCulture::model()->localized(
				$this->lang)->findAll($criteria);
		foreach ($brandCultures as $brandCulture) {
			$data[$brandCulture->title] = Yii::app()->createUrl(
					'brandCulture/view', array(
							'id' => $brandCulture->brand_culture_id));
		}

		$criteria = new CDbCriteria();
		$criteria->addSearchCondition($aliasPrefix . 'content', $this->keyword);
		$news = News::model()->localized($this->lang)->findAll($criteria);
		foreach ($news as $article) {
			$data[$article->title] = Yii::app()->createUrl('news/view', array(
					'id' => $article->news_id));
		}

		$criteria = new CDbCriteria();
		$criteria->addSearchCondition($aliasPrefix . 'content', $this->keyword);
		$dialogues = Dialogue::model()->localized($this->lang)->findAll($criteria);
		foreach ($dialogues as $dialogue) {
			$data[$dialogue->title] = Yii::app()->createUrl('dialogue/view', array(
					'id' => $dialogue->dialogue_id));
		}

		$criteria = new CDbCriteria();
		$criteria->addSearchCondition($aliasPrefix . 'content', $this->keyword);
		$magazineArticles = MagazineArticle::model()->localized(
				$this->lang)->findAll($criteria);
		foreach ($magazineArticles as $magazineArticle) {
			$data[$magazineArticle->title] = Yii::app()->createUrl(
					'magazineArticle/view', array(
							'id' => $magazineArticle->magazine_article_id));
		}

		$criteria = new CDbCriteria();
		$criteria->addSearchCondition($aliasPrefix . 'content', $this->keyword);
		$pages = Page::model()->localized($this->lang)->findAll($criteria);
		foreach ($pages as $page) {
			$data[$page->title] = Yii::app()->createUrl('page/view', array(
					'id' => $page->page_id));
		}

		return $data;
	}

	public function getLink() {
		switch ($this->related_type) {
			case 'brand_culture':
				$brandCulture = BrandCulture::model()->localized($this->lang)->findByPK($this->related_id);
				if (is_null($brandCulture) == false) {
					return CHtml::link($brandCulture->title, Yii::app()->createUrl('brandCulture/view', array(
							'id' => $brandCulture->brand_culture_id
					)));
				} else {
					return false;
				}
				break;

			case 'news':
				$news = News::model()->localized($this->lang)->findByPK($this->related_id);
				if (is_null($news) == false) {
					return CHtml::link($news->title, Yii::app()->createUrl('news/view', array(
							'id' => $news->news_id
					)));
				} else {
					return false;
				}
				break;

			case 'dialogue':
				$dialogue = Dialogue::model()->localized($this->lang)->findByPK($this->related_id);
				if (is_null($dialogue) == false) {
					return CHtml::link($dialogue->title, Yii::app()->createUrl('dialogue/view', array(
							'id' => $dialogue->dialogue_id
					)));
				} else {
					return false;
				}
				break;

			case 'magazine_article':
				$magazineArticle = MagazineArticle::model()->localized($this->lang)->findByPK($this->related_id);
				if (is_null($magazineArticle) == false) {
					return CHtml::link($magazineArticle->title, Yii::app()->createUrl('magazineArticle/view', array(
							'id' => $magazineArticle->magazine_article_id
					)));
				} else {
					return false;
				}
				break;

			case 'page':
				$page = Page::model()->localized($this->lang)->findByPK($this->related_id);
				if (is_null($page) == false) {
					return CHtml::link($page->title, Yii::app()->createUrl('page/view', array(
							'id' => $page->page_id
					)));
				} else {
					return false;
				}
				break;
			
			case 'home':
				return CHtml::link('首页', '#');
				break;

			default:
				return false;
				break;
		}		
	}

	public static function replaceWithLink($content, $lang = null, 
			$relatedType = null, $relatedId = null, $linkOptions = array()) {
		if (is_null($lang)) {
			$lang = Yii::app()->language;
		}

		$criteria = new CDbCriteria();
		$criteria->compare('lang', $lang);
		$criteria->order = 'keyword ASC';
		$internalLinkKeywords = self::model()->findAll($criteria);
		if (count($internalLinkKeywords)) {
			$matchKeywordList = array();
			foreach ($internalLinkKeywords as $internalLinkKeyword) {
				if (stripos($content, $internalLinkKeyword->keyword)) {
					$matchKeywordList[$internalLinkKeyword->internal_link_keyword_id] = 
							$internalLinkKeyword;
				}
			}

			$relatedList = array();
			foreach ($matchKeywordList as $matchKeyword) {
				if (isset($relatedList[$matchKeyword->related_type]) == false) {
					$relatedList[$matchKeyword->related_type] = array();
				}

				if (isset($relatedList[$matchKeyword->related_type][$matchKeyword->related_id]) == false) {
					$relatedList[$matchKeyword->related_type][$matchKeyword->related_id] = 
							array();
				}

				$relatedList[$matchKeyword->related_type][$matchKeyword->related_id][] = 
						$matchKeyword->keyword;
			}

			if (is_null($relatedType) == false && is_null($relatedId) == false) {
				unset($relatedList[$relatedType][$relatedId]);
			}

			$replaceList = array();
			foreach ($relatedList as $relatedType => $relatedIdList) {				
				switch ($relatedType) {
					case 'brand_culture':
						$criteria = new CDbCriteria();
						$criteria->addInCondition('brand_culture_id', 
								array_keys($relatedIdList));
						$brandCultures = BrandCulture::model()->localized()->findAll(
								$criteria);
						foreach ($brandCultures as $brandCulture) {
							foreach ($relatedIdList as $relatedId => $keyword) {
								if ($brandCulture->brand_culture_id == $relatedId) {
									foreach ($keywords as $keyword) {
										$replaceList[$keyword] = $brandCulture->getPermalink();
									}
								}
							}
						}
						break;

					case 'news':
						$criteria = new CDbCriteria();
						$criteria->addInCondition('news_id', 
								array_keys($relatedIdList));
						$news = News::model()->localized()->findAll($criteria);
						foreach ($news as $article) {
							foreach ($relatedIdList as $relatedId => $keywords) {
								if ($article->news_id == $relatedId) {
									foreach ($keywords as $keyword) {
										$replaceList[$keyword] = $article->getPermalink();
									}
								}
							}
						}
						break;

					case 'dialogue':
						$criteria = new CDbCriteria();
						$criteria->addInCondition('dialogue_id', 
								array_keys($relatedIdList));
						$dialogues = Dialogue::model()->localized()->findAll(
								$criteria);
						foreach ($dialogues as $dialogue) {
							foreach ($relatedIdList as $relatedId => $keywords) {
								if ($dialogue->dialogue_id == $relatedId) {
									foreach ($keywords as $keyword) {
										$replaceList[$keyword] = $dialogue->getPermalink();
									}
								}
							}
						}
						break;

					case 'magazine_article':
						$criteria = new CDbCriteria();
						$criteria->addInCondition('magazine_article_id', 
								array_keys($relatedIdList));
						$magazineArticles = MagazineArticle::model()->localized()->findAll(
								$criteria);
						foreach ($magazineArticles as $magazineArticle) {
							foreach ($relatedIdList as $relatedId => $keywords) {
								if ($magazineArticle->magazine_article_id == $relatedId) {
									foreach ($keywords as $keyword) {
										$replaceList[$keyword] = $magazineArticle->getPermalink();
									}
								}
							}
						}
						break;

					case 'page':
						$criteria = new CDbCriteria();
						$criteria->addInCondition('page_id', 
								array_keys($relatedIdList));
						$pages = Page::model()->localized()->findAll($criteria);
						foreach ($pages as $page) {
							foreach ($relatedIdList as $relatedId => $keywords) {
								if ($page->page_id == $relatedId) {
									foreach ($keywords as $keyword) {
										$replaceList[$keyword] = $page->getPermalink();
									}
								}
							}
						}
						break;
						
					case 'home':
						foreach ($relatedIdList as $relatedId => $keywords) {
							foreach ($keywords as $keyword) {
								$replaceList[$keyword] = Yii::app()->createUrl('site/index');
							}
						}
						break;

					default:
						return false;
						break;
				}
			}

			$currentLinkOptions = $linkOptions;
			if (isset($currentLinkOptions['class'])) {
				$currentLinkOptions['class'] .= ' internal-link';
			} else {
				$currentLinkOptions['class'] = 'internal-link';
			}

			foreach ($replaceList as $keyword => $linkUrl) {
				$content = self::replaceOnce($keyword, $linkUrl, 
						$currentLinkOptions, $content);
			}
		}

		return $content;
	}

	public static function convert2Array($keywords) {
		$keywordList = explode(',', $keywords);
		$keywordList = array_unique($keywordList);
		return $keywordList;
	}

	public static function replaceOnce($needle, $linkUrl, $linkOptions, $haystack) {
		$pos = stripos($haystack, $needle);
		if ($pos === false) {
			return $haystack;
		}

		$needle = substr($haystack, $pos, strlen($needle));
		$replace = CHtml::link($needle, $linkUrl, $linkOptions);
		return substr_replace($haystack, $replace, $pos, strlen($needle));
	}
}

?>