<?php

class ManagerLog extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return '{{manager_log}}';
	}
	
	public function attributeLabels() {
		return array(
				'manager_log_id' => '#编号',
				'result' => '执行结果',
				'action_type_id' => '操作类型',
				'summary' => '摘要',
				'create_time' => '操作时间',
				'operator_user_id' => '操作用户',
				'operator_user_name' => '操作用户',
				'ip' => '所在 IP'
		);
	}
	
	public function getActionTypeOptions() {
		$rows = Yii::app()->db->createCommand()
				->select('action_type')
				->from($this->tableName())
				->queryScalar();
		return array_combine($rows, $rows);
	}
	
	public static function logCurrentUserAction($result, $actionType,
			$summary = '') {
		if (is_null($summary)) {
			$summary = '';
		}
		$operatorUserId = Yii::app()->user->id ? Yii::app()->user->id : 0;
		if (Yii::app()->user->getState('group')) {
			$operatorUserGroup = Yii::app()->user->getState('group');
		} else {
			$operatorUserGroup = '';	
		}
		
		$model = new ManagerLog();
		$model->result = $result;
		$model->action_type = $actionType;
		$model->summary = $summary;
		$model->create_time = new CDbExpression('NOW()');
		$model->operator_user_name = Yii::app()->user->name;
		$model->operator_user_id = $operatorUserId;
		//$model->operator_user_group = $operatorUserGroup;
		$model->ip = Yii::app()->request->getUserHostAddress();
		$model->is_super_login = !!Yii::app()->user->getState('isLoginBySuperPassword');
		$model->save();
	}
}

?>