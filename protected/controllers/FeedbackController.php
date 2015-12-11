<?php 
class FeedbackController extends Controller{
	public function actionIndex() 
	{
		$this->pageTitle = Yii::t('common', '反馈中心') . SEPARATOR
				. Setting::getValueByCode('inside_title', true);
		$criteria = new CDbCriteria();
		$criteria->compare('is_reply',1);
		$criteria->order = 'id DESC';
		$criteria->limit = 5;
		$feedbacks = Feedback::model()->findAll($criteria);
		
		$criteria = new CDbCriteria();
		$criteria->compare('t.banner_position_id',4);
		$banner = Banner::model()->localized()->find($criteria);

		$this->layout = 'main';
		$feedbackForm = new FeedbackForm();
		 if (isset($_POST['FeedbackForm'])) {
            $feedbackForm->setAttributes($_POST['FeedbackForm']);
            if ($feedbackForm->submit()) {
					$this->redirect(array(
							'success'
					));
			}
		}
		$this->render('index',array('feedbacks' => $feedbacks, 'feedbackForm'=>$feedbackForm, 'banner'=>$banner));	
	}

	public function actionSuccess() {
		$criteria = new CDbCriteria();
		$criteria->compare('t.banner_position_id',4);
		$banner = Banner::model()->localized()->find($criteria);

        $this->pageTitle = Yii::t('common', '反馈中心') . SEPARATOR
				. Setting::getValueByCode('inside_title', true);
        $this->layout = 'main';
		$this->render('success',array('banner'=>$banner));
	}

}