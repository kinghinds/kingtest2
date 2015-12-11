<?php

class JobSidebar extends CWidget {
	public function run() {
		$currentMenuId = Yii::app()->controller->menuId;
		$arr[0] = $currentMenuId;

		$criteria = new CDbCriteria();
		$criteria->order = 'department_id ASC';
		$departments = Department::model()->localized()->findAll($criteria);
        foreach($departments as $i=>$department) {
            $items[$i] = array(
                'label'=>$department->title,
                'url'=>Yii::app()->createUrl('job/index', array('id' => $department->primaryKey)),
				'active'=>$department->primaryKey == $currentMenuId,
            );
        }
		//print_r($items);exit;
		$this->render('jobSidebar', array(
				'items' => $items			
			));
    }
}

?>