<?php

class JobApply {
	const UPLOAD_IMAGE_PATH = 'upload/temp/';

	public static function clear() {
		$session = new CHttpSession;
		$session->open();
		unset($_SESSION['job_apply_job_id']);
		unset($_SESSION['job_apply_step']);
		unset($_SESSION['job_apply_form1']);
		unset($_SESSION['job_apply_form2']);
		unset($_SESSION['job_apply_form3']);
		$session->close();
	}

	public static function setJobId($jobId) {
		$session = new CHttpSession;
		$session->open();
		$_SESSION['job_apply_job_id'] = $jobId;
		$session->close();
	}

	public static function getJobId() {
		$session = new CHttpSession;
		$session->open();
		if (isset($_SESSION['job_apply_job_id'])) {
			$arr = intval($_SESSION['job_apply_job_id']);
		} else {
			$arr = false;
		}
		$session->close();
		return $arr;
	}

	public static function setStep($step) {
		$session = new CHttpSession;
		$session->open();
		$_SESSION['job_apply_step'] = $step;
		$session->close();
	}

	public static function getStep() {
		$session = new CHttpSession;
		$session->open();
		if (isset($_SESSION['job_apply_step'])) {
			$arr = intval($_SESSION['job_apply_step']);
		} else {
			$arr = false;
		}
		$session->close();
		return $arr;
	}

	public static function setForm1($arr) {
		$session = new CHttpSession;
		$session->open();
		$_SESSION['job_apply_form1'] = CJSON::encode($arr);
		$session->close();
	}

	public static function getForm1() {
		$session = new CHttpSession;
		$session->open();
		if (isset($_SESSION['job_apply_form1'])) {
			$arr = CJSON::decode($_SESSION['job_apply_form1']);
		} else {
			$arr = array();
		}
		$session->close();
		return $arr;
	}

	public static function setForm2($arr) {
		$session = new CHttpSession;
		$session->open();
		$_SESSION['job_apply_form2'] = CJSON::encode($arr);
		$session->close();
	}

	public static function getForm2() {
		$session = new CHttpSession;
		$session->open();
		if (isset($_SESSION['job_apply_form2'])) {
			$arr = CJSON::decode($_SESSION['job_apply_form2']);
		} else {
			$arr = array();
		}
		$session->close();
		return $arr;
	}

	public static function setForm3($arr) {
		$session = new CHttpSession;
		$session->open();
		$_SESSION['job_apply_form3'] = CJSON::encode($arr);
		$session->close();
	}

	public static function getForm3() {
		$session = new CHttpSession;
		$session->open();
		if (isset($_SESSION['job_apply_form3'])) {
			$arr = CJSON::decode($_SESSION['job_apply_form3']);
		} else {
			$arr = array();
		}
		$session->close();
		return $arr;
	}
}

?>