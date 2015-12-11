<?php

class JobApplyForm1 extends CFormModel {
	const DEFAULT_HOME_PHONE_AREA_CODE = '区号';
	const DEFAULT_HOME_PHONE_NUMBER = '固定电话';
	public $jobId;
	public $name;
	public $formerName;
	public $englishName;
	public $gender;
	public $education;
	public $nation;
	public $maritalStatus;
	public $hasPassport;
	public $residentRegistrationNature;
	public $birthYear;
	public $birthMonth;
	public $birthDay;
	public $foreignLanguage;
	public $selfIdCard;
	public $spouseIdCard;
	public $socialSecurity;
	public $housingProvidentFund;
	public $nativePlaceProvinceId;
	public $nativePlaceProvince;
	public $nativePlaceCityId;
	public $nativePlaceCity;
	public $nativePlaceAddress;
	public $residentRegistrationLocation;
	public $currentAddress;
	public $statutoryAddress;
	public $homePhoneAreaCode;
	public $homePhoneNumber;
	public $mobile;
	public $postcode;
	public $email;
	public $emergencyContactPerson;
	public $emergencyContactFamilyRelationship;
	public $emergencyContactMobile;
	public $imageFileName;
	public $validationCode;

	public function attributeLabels() {
		return array(
				'name' => '姓名',
				'formerName' => '曾用名',
				'englishName' => '英文名',
				'gender' => '性别',
				'education' => '学历',
				'nation' => '民族',
				'maritalStatus' => '婚姻状况',
				'hasPassport' => '有效护照',
				'residentRegistrationNature' => '户口性质',
				'birthday' => '出生日期',
				'birthYear' => '出生日期年份',
				'birthMonth' => '出生日期月份',
				'birthDay' => '出生日期日数',
				'foreignLanguage' => '外语语种及等级',
				'selfIdCard' => '本人身份证号码',
				'spouseIdCard' => '配偶身份证号码',
				'socialSecurity' => '社会保险号',
				'housingProvidentFund' => '住房公积金',
				'nativePlaceProvinceId' => '籍贯省份',
				'nativePlaceProvince' => '籍贯省份',
				'nativePlaceCityId' => '籍贯城市',
				'nativePlaceCity' => '籍贯城市',
				'nativePlaceAddress' => '籍贯',
				'residentRegistrationLocation' => '户口所在地',
				'currentAddress' => '现住地址',
				'statutoryAddress' => '法定通讯地址',
				'homePhone' => '家庭电话',
				'homePhoneAreaCode' => '家庭电话区号',
				'homePhoneNumber' => '家庭电话固定电话',
				'mobile' => '移动电话',
				'postcode' => '邮政编码',
				'email' => '电子邮箱',
				'emergencyContactPerson' => '紧急联系人姓名',
				'emergencyContactFamilyRelationship' => '家属关系',
				'emergencyContactMobile' => '移动电话',
				'imageFileName' => '照片',
				'validationCode' => '验证码',
		);
	}

	public function rules() {
		return array(
				array('name, education', 'required'),
				array('name, formerName, englishName, education, nation, foreignLanguage,' 
						. 'selfIdCard, spouseIdCard, socialSecurity, '
						. 'housingProvidentFund, nativePlaceProvinceId, '
						. 'nativePlaceCityId, nativePlaceAddress, '
						. 'residentRegistrationLocation, currentAddress, '
						. 'statutoryAddress, homePhoneAreaCode, homePhoneNumber, '
						. 'mobile, postcode, email, emergencyContactPerson, '
						. 'emergencyContactFamilyRelationship, '
						. 'emergencyContactMobile, imageFileName, ip',
						'safe'), 
				array('gender, maritalStatus, hasPassport, residentRegistrationNature', 
						'type', 'type' => 'boolean'), 
				array('birthYear, birthMonth, birthDay', 
						'type', 'type' => 'integer'), 
				array('email', 'email', 'allowEmpty' => true),
				array('validationCode', 'captcha', 'allowEmpty' => false)
		);
	}

	public function getGenderOptions() {
		return JobApplication::model()->getGenderOptions();
	}

	public function getGender() {
		$options = $this->getGenderOptions();
		return isset($options[$this->gender]) ? $options[$this->gender] : false;
	}

	public function getEducationOptions() {
		return JobApplication::model()->getEducationOptions();
	}

	public function getEducationText() {
		$options = $this->getEducationOptions();
		return isset($options[$this->education]) ? $options[$this->education] : false;
	}

	public function getMaritalStatusOptions() {
		return JobApplication::model()->getMaritalStatusOptions();
	}

	public function getMaritalStatus() {
		$options = $this->getMaritalStatusOptions();
		return isset($options[$this->maritalStatus]) ? $options[$this->maritalStatus] : false;
	}

	public function getHasPassportOptions() {
		return JobApplication::model()->getHasPassportOptions();
	}

	public function getHasPassport() {
		$options = $this->getHasPassportOptions();
		return isset($options[$this->hasPassport]) ? $options[$this->hasPassport] : false;
	}

	public function getResidentRegistrationNatureOptions() {
		return JobApplication::model()->getResidentRegistrationNatureOptions();
	}

	public function getResidentRegistrationNature() {
		$options = $this->getResidentRegistrationNatureOptions();
		return isset($options[$this->residentRegistrationNature]) ? $options[$this->residentRegistrationNature] : false;
	}

	public function getBirthYearOptions() {
		$yearList = range(intval(date('Y')) - 12, 1950);
		$yearOptions = array_combine($yearList, $yearList);
		return $yearOptions;
	}

	public function getBirthMonthOptions() {
		$monthList = range(1, 12);
		$monthOptions = array_combine($monthList, $monthList);
		return $monthOptions;
	}

	public function getBirthDayOptions($year, $month) {
		$time = mktime(0, 0, 0, $month, 1, $year);
		$lastDay = intval(date('t', $time));
		$dayList = range(1, $lastDay);
		$dayOptions = array_combine($dayList, $dayList);
		return $dayOptions;
	}

	public function getProvinceOptions() {
		$criteria = new CDbCriteria();
		$criteria->order = 'province_id ASC';
		$provinces = Province::model()->findAll($criteria);
		$provinceOptions = CHtml::listData($provinces, 'province_id', 
				'province_name');
		return $provinceOptions;
	}

	public function getCityOptions($provinceId) {
		$criteria = new CDbCriteria();
		$criteria->compare('province_id', $provinceId);
		$criteria->order = 'city_id ASC';
		$cities = City::model()->findAll($criteria);
		$cityOptions = CHtml::listData($cities, 'city_id', 'city_name');
		return $cityOptions;
	}

	public function getNativePlaceProvince() {
		$province = Province::model()->findByPk($this->nativePlaceProvinceId);
		return is_null($province) ? false : $province->province_name;
	}

	public function getNativePlaceCity() {
		$city = City::model()->findByPk($this->nativePlaceCityId);
		return is_null($city) ? false : $city->city_name;
	}

	public function getImageUrl() {
		$imageUrl = false;
		if (empty($this->imageFileName) == false) {
			$imageFilePath = Helper::mediaPath(JobApply::UPLOAD_IMAGE_PATH 
					. $this->imageFileName);
			if (is_file($imageFilePath)) {
				$imageUrl = Helper::mediaUrl(JobApply::UPLOAD_IMAGE_PATH 
					. $this->imageFileName);
			}
		}
		return $imageUrl;
	}

	public function submit() {
		if ($this->validate()) {
			if ($this->homePhoneAreaCode == self::DEFAULT_HOME_PHONE_AREA_CODE) {
				$this->homePhoneAreaCode = '';
			}
			if ($this->homePhoneNumber == self::DEFAULT_HOME_PHONE_NUMBER) {
				$this->homePhoneNumber = '';
			}
			JobApply::setJobId($this->jobId);
			JobApply::setStep(1);
			JobApply::setForm1($this->attributes);
			return true;
		}

		return false;
	}
}

?>