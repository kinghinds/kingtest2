<?php

class JobApplyForm2 extends CFormModel {
	public $education;

	public function attributeLabels() {
		return array(
				'education' => '教育经历'
		);
	}

	public function rules() {
		return array(
				array('education', 'required'),
				array('education', 'type', 'type' => 'array'),
				array('education', 'validateEducation')
		);
	}

	public function validateEducation() {
		if ($this->hasErrors('education') == false) {
			$index = 1;
			foreach ($this->education as $key => $item) {
				if (isset($item['enterDate']) == false || empty($item['enterDate'])) {
					$this->addError('education', strtr('第{row_number}行数据入学日期为空白', array(
							'{row_number}' => $index
					)));
				} else if (strtotime($item['enterDate']) <= 0) {
					$this->addError('education', strtr('第{row_number}行数据入学日期格式有误', array(
							'{row_number}' => $index
					)));
				}
				if (isset($item['draduationDate']) == false || empty($item['draduationDate'])) {
					$this->addError('education', strtr('第{row_number}行数据毕业日期为空白', array(
							'{row_number}' => $index
					)));
				} else if (strtotime($item['draduationDate']) <= 0) {
					$this->addError('education', strtr('第{row_number}行数据毕业日期格式有误', array(
							'{row_number}' => $index
					)));
				}
				if (isset($item['enterDate']) && empty($item['enterDate']) == false 
						&& strtotime($item['enterDate']) > 0 
						&& isset($item['draduationDate']) 
						&& empty($item['draduationDate']) == false 
						&& strtotime($item['draduationDate']) > 0) {
					if (strtotime($item['enterDate']) >= strtotime($item['draduationDate'])) {
						$this->addError('education', strtr('第{row_number}行数据入学日期不能大于或等于毕业日期', array(
								'{row_number}' => $index
						)));
					}
				}
				if (isset($item['school']) == false || empty($item['school'])) {
					$this->addError('education', strtr('第{row_number}行数据学校为空白', array(
							'{row_number}' => $index
					)));
				}
				if (isset($item['professional']) == false || empty($item['professional'])) {
					$this->addError('education', strtr('第{row_number}行数据专业为空白', array(
							'{row_number}' => $index
					)));
				}
				if (isset($item['learningStyle']) == false || empty($item['learningStyle'])) {
					$this->addError('education', strtr('第{row_number}行数据学习方式为空白', array(
							'{row_number}' => $index
					)));
				}
				if (isset($item['education']) == false || empty($item['education'])) {
					$this->addError('education', strtr('第{row_number}行数据学历/学位为空白', array(
							'{row_number}' => $index
					)));
				}				
				$index++;
			}
		}
	}

	public function getLearningStyleOptions() {
		return JobApplicationEducation::model()->getLearningStyleOptions();
	}

	public function getLearningStyle($learningStyle) {
		$options = $this->getLearningStyleOptions();
		return isset($options[$learningStyle]) ? $options[$learningStyle] : false;
	}

	public function getEducationOptions() {
		return JobApplicationEducation::model()->getEducationOptions();
	}

	public function getEducationText($education) {
		$options = $this->getEducationOptions();
		return isset($options[$education]) ? $options[$education] : false;
	}

	public function submit() {
		if ($this->validate()) {
			JobApply::setStep(2);
			JobApply::setForm2($this->attributes);
			return true;
		}
		return false;
	}
}

?>