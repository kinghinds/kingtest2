<?php

class SiteController extends Controller {

	public function filters() {
		return array('accessControl');
	}

	public function accessRules() {
		return array(
			array('users' => array('@')),
			array('deny', 'allow' => array('login'), 'users' => array('*'))
		);
	}

	public function actions() {
		return array(
				'captcha' => array(
						'class' => 'CCaptchaAction', 
						'backColor' => 0xFFFFFF
				),
				'page' => array('class' => 'CViewAction')
		);
	}

	public function actionError() {
		$error = Yii::app()->errorHandler->error;
		if ($error) {
			if (Yii::app()->request->isAjaxRequest) {
				echo $error['message'];
			} else {
				$this->render('error', $error);
			}
		}
	}

	public function actionIndex() {
		$this->render('index');
	}

	public function actionSetting() {
		if (Yii::app()->user->checkAccess('updateSetting') == false) {
			throw new CHttpException(403);
		}

		$normalCodes = array(
				'system_maintaining', 
				'footer_js',
				'google_analytics_account', 
				'google_analytics_password', 
				'google_analytics_report_id'
		);
		$multilangCodes = array(
				'home_title', 
				'home_internal_link_keywords', 
				'inside_title', 
				'magazine_footer', 
				'copyright',
				'address',
				'email',
				'meta_keywords', 
				'meta_description'
		);

		$models = array();
		foreach ($normalCodes as $code) {
			$models[$code] = Setting::model()->findByAttributes(array(
					'code' => $code
			));
		}
		foreach ($multilangCodes as $code) {
			$models[$code] = Setting::model()->multilingual()->findByAttributes(array(
					'code' => $code
			));
		}

		// robots.txt
		$robotsFilePath = Helper::mediaPath('robots.txt', FRONTEND);
		if (file_exists ($robotsFilePath)) {
			$robots = file_get_contents($robotsFilePath);
		} else if (is_writable($robotsFilePath)) {
			$handle = fopen($robotsFilePath, 'a');
			if ($handle != false) {
				$fp = fopen($robotsFilePath, 'w');
				fclose($fp);
			}
			$robots = '';
		} else {
			$robots = '';
		}

		if ($_POST) {
			foreach (array_merge($normalCodes, $multilangCodes) as $code) {
				if (isset($_POST[$code])) {
					$models[$code]->setAttributes($_POST[$code]);
				} else {
					$models[$code]->setAttributes(array('value' => ''));
				}
			}
			if (isset($models['home_internal_link_keywords'])) {
				$models['home_internal_link_keywords']['value'] =
						Helper::arrangeKeywords($_POST['home_internal_link_keywords']['value']);
			}
			foreach ($models as $model) {
				$model->save();
			}

			// 首页内链关键词
			if (isset($models['home_internal_link_keywords'])) {
				foreach (I18nHelper::getFrontendLanguages(false) as $lang => $attr) {
					if ($lang == I18nHelper::getFrontendSourceLanguage()) {
						InternalLinkKeyword::model()->insertOrUpdate(
							'home', 0, $lang, 
							$models['home_internal_link_keywords']['value']);
					} else {
						InternalLinkKeyword::model()->insertOrUpdate(
							'home', 0, $lang, 
							$models['home_internal_link_keywords']->i18nFormData['value_' . $lang]);
					}
				}	
			}

			// robots.txt
			if (is_writable($robotsFilePath)) {
				$robots = Yii::app()->request->getPost('robots');
				$handle = fopen($robotsFilePath, 'a');
				if ($handle != false) {
					$fp = fopen($robotsFilePath, 'w');
					fwrite($handle, $robots);
					fclose($fp);
				}
			}
			
			$this->setFlashMessage('系统设置已保存');
			$this->redirect($this->getReturnUrl());
		}

		$this->breadcrumbs = array('系统设置');
		$this->render('setting', array(
				'models' => $models,
				'robots' => $robots,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionLogin() {
		$model = new LoginForm();
		if (isset($_POST['LoginForm'])) {
			$model->attributes = Yii::app()->request->getPost('LoginForm');
			if ($model->validate() && $model->login() 
					&& Yii::app()->user->isGuest == false) {
				if (empty(Yii::app()->user->returnUrl)) {
					if (!YII_DEBUG && $model->login_password == '123456') {
						$this->setFlashMessage(
								'为了网站数据的安全性, 请立即更改您的用户密码', 
								'warn'
						);
						$this->redirect(array('password'));
					} else {
						$this->redirect(Yii::app()->homeUrl);
					}
				} else {
					$this->redirect(Yii::app()->user->returnUrl);
				}
			} else {
				ManagerLog::logCurrentUserAction(0, '登录', strtr(
						"username: {username} \n password: {password}", array(
								"{username}" => $model->loginName,
								"{password}" => $model->loginPassword
						)
				));
			}
		}

		$this->layout = false;
		$this->render('login', array(
				'model' => $model
		));
	}

	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionPassword() {
		$model = new UpdatePasswordForm();
		if (isset($_POST['UpdatePasswordForm'])) {
			$model->attributes = Yii::app()->request->getPost('UpdatePasswordForm');
			if ($model->validate()) {
				Manager::model()->updateByPk(Yii::app()->user->id, array(
						'login_password' => md5($model->new_password)
				));
				$this->setFlashMessage('您的密码已更新, 新密码已生效');
				$this->redirect($this->getReturnUrl());
			}
		}

		$this->breadcrumbs = array('修改密码');
		$this->render('password', array(
				'model' => $model,
				'returnUrl' => $this->getReturnUrl()
		));
	}

	public function actionCleanCache() {
		if (Yii::app()->user->checkAccess('cleanCache') == false) {
			throw new CHttpException(403);
		}

		$ret = array();

		try {
			// 清除前台缓存
			$path = Yii::getPathOfAlias('frontendBasePath') . '/runtime/cache/';
			if (is_dir($path)) {
				foreach (CFileHelper::findFiles($path) as $file) {
					unlink($file);
				}
			}

			// 清除后台缓存
			$path = Yii::getPathOfAlias('application') . '/runtime/cache/';
			if (is_dir($path)) {
				foreach (CFileHelper::findFiles($path) as $file) {
					unlink($file);
				}
			}

			$ret = array(
					'result' => true,
					'message' => '清除网站缓存成功'
			);
		} catch (Exception $e) {
			$ret = array(
					'result' => false,
					'message' => '清除网站缓存失败\n错误信息: ' . $e->getMessage()
			);
		}

		if (Yii::app()->request->isAJAXRequest) {
			echo CJSON::encode($ret);
			Yii::app()->end();
		} else {
			$this->breadcrumbs = array('清除缓存');
			$this->render('/message', array(
					'message' => nl2br($ret['message'])
			));
		}
	}

	public function actionTinymceImageUpload() {
		require_once('Image.php');

		if ($_FILES) {
			// 检查图片是否存在
			$file = CUploadedFile::getInstanceByName('imageFile');
			if (empty($file) == true) {
				//$this->tinymceShowMessage(false, null, '请选择需要上传的图片');
				$this->tinymceShowMessage(false, null,
						'\u8BF7\u9009\u62E9\u9700\u8981\u4E0A\u4F20\u7684\u56FE\u7247');
			}

			// 检查图片后缀是否被允许
			$imageExtList = array('jpg', 'jpeg', 'gif', 'png');
			if (in_array(strtolower($file->extensionName), $imageExtList) == false) {
				//$this->tinymceShowMessage(false, null, '不允许上传的图片格式');
				$this->tinymceShowMessage(false, null,
						'\u4E0D\u5141\u8BB8\u4E0A\u4F20\u7684\u56FE\u7247\u683C\u5F0F');
			}

			// 检查图片是否可以识别
			list($width, $height, $type, $attr) = getimagesize($file->tempName);
			if (empty($width) || empty($height)) {
				//$this->tinymceShowMessage(false, null, $file->name . ' 图片无法识别');
				$this->tinymceShowMessage(false, null, $file->name
						. ' \u56FE\u7247\u65E0\u6CD5\u8BC6\u522B');
			}

			try {
				// 保存原图片				
				$fileName = md5($file->tempName . uniqid()) . '.'
						. $file->extensionName;
				$filePath = Helper::mediaPath('upload/editor/orig/'
						. $fileName, FRONTEND);
				$file->saveAs($filePath);

				list($width, $height, $type, $attr) = getimagesize($filePath);
				$imageWidthLimit = Yii::app()->params['editorUploadImageWidthLimit'];
				if ($width > $imageWidthLimit) {
					// 裁切缩略图
					$image = new Image($filePath);
					$image->resize(
							$imageWidthLimit, 
							$imageWidthLimit / $width * $height, 
							Image::RESIZE_SPECIFY_WIDTH
					)->save(Helper::mediaPath('upload/editor/large/'
							. $fileName, FRONTEND)
					);
					// $this->tinymceShowMessage(true, Helper::mediaUrl('upload/editor/large/' . $fileName, 'FRONTEND'), '图片上传成功');
					$this->tinymceShowMessage(
							true, 
							Helper::mediaUrl('upload/editor/large/' . $fileName, FRONTEND),
							'\u56FE\u7247\u4E0A\u4F20\u6210\u529F'
					);
				} else {
					// $this->tinymceShowMessage(true, Helper::mediaUrl('upload/orig/' . $fileName, 'FRONTEND'), '图片上传成功');
					$this->tinymceShowMessage(
							true,
							Helper::mediaUrl('upload/editor/orig/' . $fileName, 
									FRONTEND),
							'\u56FE\u7247\u4E0A\u4F20\u6210\u529F'
					);
				}
			} catch (Exception $e) {
				// $this->tinymceShowMessage(false, null, '图片上传失败');
				$this->tinymceShowMessage(
						false, 
						null, 
						'\u56FE\u7247\u4E0A\u4F20\u5931\u8D25'
				);
			}
		}

		$editorBaseUrl = Yii::app()->request->getQuery('editor_base_url');
		$this->layout = false;
		$this->render('tinymceImageUpload', array(
				'editorBaseUrl' => $editorBaseUrl
		));
	}

	public function tinymceShowMessage($isUploaded, $imageUrl, $message) {
		$editorBaseUrl = Yii::app()->request->getPost('editor_base_url');
		$this->layout = false;
		$this->render('tinymceImageMessage', array(
				'editorBaseUrl' => $editorBaseUrl,
				'isUploaded' => $isUploaded,
				'imageUrl' => $imageUrl, 
				'message' => $message
		));
		Yii::app()->end();
	}

	public function actionGoogleAnalytics() {
		if (Yii::app()->user->checkAccess('viewGoogleAnalytics') == false) {
			throw new CHttpException(403);
		}

		require_once('gapi.class.php');
		header('Content-type: application/json');
		try {
			$account = Setting::getValueByCode('google_analytics_account');
			$password = Setting::getValueByCode('google_analytics_password');
			$reportId = Setting::getValueByCode('google_analytics_report_id');
			if (empty($account) || empty($password) || empty($password)) {
				throw new Exception('Google 分析帐号信息未设置');
			}

			$ga = new gapi($account, $password);
			$ga->requestReportData(
					$reportId, 
					array('date'),
					array('pageviews', 'visits'),
					array('date', '-pageviews', '-visits')
			);

			$pageviews = array();
			$visits = array();

			foreach ($ga->getResults() as $result) {
				array_push($pageviews,	array(
						date('Y-n-j', strtotime($result->getDate())),
						$result->getPageviews()
				));
				array_push($visits, array(
						date('Y-n-j', strtotime($result->getDate())),
						$result->getVisits()
				));
			}
			echo CJSON::encode(array(
					'result' => true,
					'data' => array($pageviews, $visits)
			));
		} catch (Exception $e) {
			echo CJSON::encode(array(
					'result' => false, 
					'message' => $e->getMessage()
			));
		}
		Yii::app()->end();
	}

	public function actionExportSqlFile() {
		if (Yii::app()->user->checkAccess('exportSqlFile') == false) {
			throw new CHttpException(403);
		}

		header('Pragma: public');
		header('Expires: 0');
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=backup.sql');
		header('Content-Transfer-Encoding: binary');

		$tables = Helper::getTables();
		foreach ($tables as $table) {
			if (Yii::app()->db->tablePrefix) {
				if (strpos($table, Yii::app()->db->tablePrefix) === false) {
					$status = false;
				} else {
					$status = true;
				}
			} else {
				$status = true;
			}

			if ($status) {
				echo 'TRUNCATE TABLE `' . $table . '`;' . "\n";
				$rows = Yii::app()->db
						->createCommand("SELECT * FROM `" . $table . "`")
						->queryAll();
				foreach ($rows as $result) {
					$fields = '';
					foreach (array_keys($result) as $value) {
						$fields .= '`' . $value . '`, ';
					}
					$values = '';
					foreach (array_values($result) as $value) {
						$value = str_replace(
								array("\x00", "\x0a", "\x0d", "\x1a"),
								array('\0', '\n', '\r', '\Z'), $value);
						$value = str_replace(array("\n", "\r", "\t"),
								array('\n', '\r', '\t'), $value);
						$value = str_replace('\\', '\\\\', $value);
						$value = str_replace('\'', '\\\'', $value);
						$value = str_replace('\\\n', '\n', $value);
						$value = str_replace('\\\r', '\r', $value);
						$value = str_replace('\\\t', '\t', $value);
						$values .= '\'' . $value . '\', ';
					}
					echo 'INSERT INTO `' . $table . '` ('
							. preg_replace('/, $/', '', $fields) . ')';
					echo ' VALUES (' . preg_replace('/, $/', '', $values)
							. ');' . "\n";
				}
				echo "\n";
			}
		}

		Yii::app()->end();
	}

	public function actionSendTestMail() {
		if (Yii::app()->request->isAjaxRequest) {
			$host = trim(Yii::app()->request->getPost('host'));
			$username = trim(Yii::app()->request->getPost('username'));
			$password = trim(Yii::app()->request->getPost('password'));
			$from = trim(Yii::app()->request->getPost('from'));
			$recipient = trim(Yii::app()->request->getPost('recipient'));

			Yii::import('application.extensions.phpmailer.JPhpMailer');
			try {
				$mail = new JPhpMailer(true);
				$mail->SetLanguage('zh_cn');
				$mail->CharSet = 'UTF-8';
				$mail->IsSMTP();
				$mail->Host = $host;
				$mail->SMTPAuth = true;
				$mail->Username = $username;
				$mail->Password = $password;
				$mail->SetFrom($from);
				$mail->Subject = 'Test Subject';
				$mail->MsgHTML('Test Content');
				$mail->AddAddress($recipient);
				if ($mail->Send()) {
					echo CJSON::encode(array('result' => true));
				} else {
					throw new CException($mail->ErrorInfo);
				}
			} catch (Exception $e) {
				echo CJSON::encode(array(
						'result' => false, 
						'message' => $e->getMessage()
				));
			}
		}
		Yii::app()->end();
	}

	public function actionRemoveAssets() {
		$path = realpath('./assets/');
		echo $this->full_rmdir($path) ? 'SUCC' : 'FAIL';
	}

	protected function full_rmdir($dirname) {
		if ($dirHandle = opendir($dirname)) {
			$old_cwd = getcwd();
			chdir($dirname);
			while ($file = readdir($dirHandle)) {
				if ($file == '.' || $file == '..') continue;
				if (is_dir($file)) {
					if (!$this->full_rmdir($file)) return false;
				} else {
					if (!unlink($file)) return false;
				}
			}
			closedir($dirHandle);
			chdir($old_cwd);
			if (!rmdir($dirname)) return false;
			return true;
		}else{
			return false;
		}
	}
}

?>