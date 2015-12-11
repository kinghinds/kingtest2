<?php 
/**
 * @memo   服务中心
 * @author 邓 流 <759371065@qq.com>
 * @time   2015-04-27 10:04:14
 */
class ServerController extends Controller{
	public function actionIndex() 
	{
		$criteria = new CDbCriteria();
		$criteria->compare('t.is_released',1);
		$criteria->order = 't.sort_order ASC';
		$servers = Server::model()->localized()->findAll($criteria);
		
		$criteria = new CDbCriteria();
		$criteria->compare('t.banner_position_id',3);
		$banner = Banner::model()->localized()->find($criteria);

		$this->pageTitle = Yii::t('common', '服务中心') . SEPARATOR
				. Setting::getValueByCode('inside_title', true);
		$this->layout = 'main';
		$this->render('index',
			array(
				'servers'=>$servers,
				'banner'=>$banner,
			));

	}
	public function actionView() {
		// 当前新闻
		if (isset($_GET['id'])) {
			$id = Yii::app()->request->getQuery('id');
			$server = Server::model()->localized()->findByPk($id);
		} else {
			$server = null;
		}
		if (empty($server))
			throw new CHttpException(404);

		$criteria = new CDbCriteria();
		$criteria->compare('t.banner_position_id',3);
		$banner = Banner::model()->localized()->find($criteria);
		
		$this->layout = 'main';
		$this->pageTitle = $server->name . "-" . Yii::t('common', '服务中心');
		$this->render('view', array(
				'server' => $server,
				'banner'=>$banner,
		));
		

	}

}