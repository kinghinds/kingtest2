<?php

class JobApplyForm3 extends CFormModel {
	public $work;
	public $isSaveOnly;

	public function attributeLabels() {
		return array(
				'work' => '工作经历'
		);
	}

	public function rules() {
		return array(
				array('work', 'required'),
				array('work', 'type', 'type' => 'array'),
				array('work', 'validateWork'),
				array('isSaveOnly', 'type', 'type' => 'boolean')
		);
	}

	public function validateWork() {
		if ($this->hasErrors('work') == false) {
			$index = 1;
			foreach ($this->work as $key => $item) {
				if (isset($item['startDate']) == false || empty($item['startDate'])) {
					$this->addError('work', strtr('单位{row_number}数据开始时间为空白', array(
							'{row_number}' => ($key + 1)
					)));
				} else if (strtotime($item['startDate']) <= 0) {
					$this->addError('work', strtr('单位{row_number}数据开始时间格式有误', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['endDate']) && empty($item['endDate']) == false && strtotime($item['endDate']) <= 0) {
					$this->addError('work', strtr('单位{row_number}数据结束时间格式有误', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['startDate']) && empty($item['startDate']) == false 
						&& strtotime($item['startDate']) > 0
						&& isset($item['endDate']) && empty($item['endDate']) == false
						&& strtotime($item['endDate'])) {
					if (strtotime($item['startDate']) >= strtotime($item['endDate'])) {
						$this->addError('work', strtr('单位{row_number}数据开始时间不能大于或等于结束时间', array(
								'{row_number}' => ($key + 1)
						)));
					}
				}
				if (isset($item['companyName']) == false || empty($item['companyName'])) {
					$this->addError('work', strtr('单位{row_number}数据单位名称为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['companyNature']) == false) {
					$this->addError('work', strtr('单位{row_number}数据单位性质为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['companyPeopleNumber']) == false) {
					$this->addError('work', strtr('单位{row_number}数据单位人数为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				/*if (isset($item['postcode']) == false) {
					$this->addError('work', strtr('单位{row_number}数据邮政编码为空白', array(
							'{row_number}' => $index
					)));
				}*/
				if (isset($item['department']) == false) {
					$this->addError('work', strtr('单位{row_number}数据任职部门为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['position']) == false) {
					$this->addError('work', strtr('单位{row_number}数据担任职务为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['subordinateNumber']) == false) {
					$this->addError('work', strtr('单位{row_number}数据下属人数为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['addressProvinceId']) == false) {
					$this->addError('work', strtr('单位{row_number}数据单位地址省份为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['addressCityId']) == false) {
					$this->addError('work', strtr('单位{row_number}数据单位地址城市为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['address']) == false) {
					$this->addError('work', strtr('单位{row_number}数据单位地址为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['mainProduct']) == false) {
					$this->addError('work', strtr('单位{row_number}数据主营产品为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['salary']) == false) {
					$this->addError('work', strtr('单位{row_number}数据月薪为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['bossName']) == false) {
					$this->addError('work', strtr('单位{row_number}数据直接上司姓名为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['bossPosition']) == false) {
					$this->addError('work', strtr('单位{row_number}数据直接上司担任职务为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['contactPhone']) == false) {
					$this->addError('work', strtr('单位{row_number}数据联系电话为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['departureReason']) == false) {
					$this->addError('work', strtr('单位{row_number}数据离职原因为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['witnessName']) == false) {
					$this->addError('work', strtr('单位{row_number}数据证明人电话为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['task']) == false) {
					$this->addError('work', strtr('单位{row_number}数据主要工作内容为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				if (isset($item['performance']) == false) {
					$this->addError('work', strtr('单位{row_number}数据主要工作业绩为空白', array(
							'{row_number}' => ($key + 1)
					)));
				}
				$index++;
			}
		}
	}

	public function getProvince($provinceId) {
		$province = Province::model()->findByPk($provinceId);
		return is_null($province) ? false : $province->province_name;
	}

	public function getCity($cityId) {
		$city = City::model()->findByPk($cityId);
		return is_null($city) ? false : $city->city_name;
	}
	
	public function getCompanyNatureOptions() {
		return JobApplicationWork::model()->getCompanyNatureOptions();
	}

	public function getCompanyNature($companyNature) {
		$options = $this->getCompanyNatureOptions();
		return isset($options[$companyNature]) ? $options[$companyNature] : false;
	}

	public function getCompanyPeopleNumberOptions() {
		return JobApplicationWork::model()->getCompanyPeopleNumberOptions();
	}

	public function getCompanyPeopleNumber($companyPeopleNumber) {
		$options = $this->getCompanyPeopleNumberOptions();
		return isset($options[$companyPeopleNumber]) ? $options[$companyPeopleNumber] : false;
	}

	public function submit() {
		if ($this->validate()) {
			JobApply::setStep(3);
			JobApply::setForm3($this->attributes);
			return true;
		}
		return false;
	}
}

?>