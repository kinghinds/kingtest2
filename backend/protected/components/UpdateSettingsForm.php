<?php

class UpdateSettingsForm extends FormModel {
	public $systemMaintaining;
	public $pageCacheDuration;
	public $receiveFeedbackEmail;
	public $receiveNewOrderNotify;
	public $userVerifyCode;
	public $onlyShowAvailableJobs;
	public $extraHeadSection;
	public $extraBodySection;
	
	public function attributeLabels() {
		return array(
				'receiveFeedbackEmail' => 'Feedback Form',
				'receiveNewOrderNotify' => 'New Order Notify',
				'systemMaintaining' => '系统维护',
				'onlyShowAvailableJobs' => '只显示可用的招聘信息',
				'userVerifyCode' => '启用验证码',
				'pageCacheDuration' => '页面缓存时间',
				'extraHeadSection' => '额外头部内容',
				'extraBodySection' => '额外内容',
		);
	}
	
	public function rules() {
		return array(
				array(
						'receiveFeedbackEmail',
						'safe'
				),
				array(
						'receiveNewOrderNotify',
						'safe'
				),
				array(
						'systemMaintaining',
						'type',
						'type' => 'boolean'
				),
				array(
						'userVerifyCode',
						'type',
						'type' => 'boolean'
				),
				array(
						'onlyShowAvailableJobs',
						'type',
						'type' => 'boolean'
				),
				array(
						'pageCacheDuration',
						'type',
						'type' => 'integer'
				),
				array(
						'extraHeadSection,extraBodySection',
						'safe'
				),
		);
	}
}
