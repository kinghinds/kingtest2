<?php

class Cases extends I18nActiveRecord {

	public function i18nAttributes() {
		return array(
				'title', 
				'content', 
				'is_released'
		);
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{cases}}';
	}

	public function attributeLabels() {
		return array(
				'case_id' => '#编号',
				'slug' => '网址优化',
				'title' => '案例标题',
				'content' => '详细内容',
				'release_date' => '发布日期',
				'is_released' => '发布',
		);
	}

	public function rules() {
		return array(
				array('slug', 'validateSlug'),
				array('title, content', 'safe'),
				array(
						'release_date',
						'type',
						'type' => 'date',
						'dateFormat' => 'yyyy-MM-dd'
				),
				array('is_released', 'type', 'type' => 'boolean'),
				array('i18nFormData', 'type', 'type' => 'array')
		);
	}

	public function validateSlug() {
		if ($this->hasErrors('slug') == false) {
			$this->slug = Helper::slugFilter($this->slug);
			if (empty($this->slug) == false) {
				$model = self::model()
						->findByAttributes(array('slug' => $this->slug));
				if (empty($model) == false) {
					if (($this->isNewRecord == true)
							|| ($this->isNewRecord == false
									&& $model->primaryKey != $this->primaryKey)) {
						$this->addError('slug', '固定链接已存在');
					}
				}
			}
		}
	}

	public function getPermalink() {
		if (empty($this->slug) == false && strlen(trim($this->slug)) > 0) {
			return Yii::app()
					->createUrl('cases/view', array('slug' => $this->slug));
		} else {
			return Yii::app()
					->createUrl('cases/view', array('id' => $this->primaryKey));
		}
	}

}

?>