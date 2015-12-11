<?php

class UploadifyWidget extends CInputWidget
{
	private $body = null;
	private $options = array();
	private $callbacks = array();
	private $baseUrl;

	protected $validOptions = array(
        'auto'				=>	array('type' => 'boolean'),
        'buttonClass'		=>	array('type' => 'string'),
        'buttonCursor'		=>	array('type' => 'string'),
        'buttonImage'		=>	array('type' => 'string'),
        'buttonText'		=>	array('type' => 'string'),
        'checkExisting'		=>	array('type' => 'boolean'),
        'debug'				=>	array('type' => 'boolean'),
        'fileObjName'		=>	array('type' => 'string'),
        'fileSizeLimit'		=>	array('type' => 'integer'),
        'fileTypeDesc'		=>	array('type' => 'string'),
        'fileTypeExts'		=>	array('type' => 'string'),
        'formData'			=>	array('type' => 'array'),
        'height'			=>	array('type' => 'integer'),
		'method'			=>	array('type' => 'string', 'possibleValues' => array('POST', 'GET')),
        'multi'				=>	array('type' => 'boolean'),
        'overrideEvents'	=>	array('type' => 'array'),
        'preventCaching'	=>	array('type' => 'boolean'),
        'progressData'		=>	array('type' => 'string', 'possibleValues' => array('percentage', 'speed')),
        'queueID'			=>	array('type' => 'string'),
        'queueSizeLimit'	=>	array('type' => 'integer'),
        'removeCompleted'	=>	array('type' => 'boolean'),
        'removeTimeout'		=>	array('type' => 'integer'),
        'requeueErrors'		=>	array('type' => 'boolean'),
        'successTimeout'	=>	array('type' => 'integer'),
        'swf'				=>	array('type' => 'string'),
        'uploader'			=>	array('type' => 'string'),
        'uploadLimit'		=>	array('type' => 'integer'),
        'width'				=>	array('type' => 'integer')                
	);

	/**
	 * Valid callbacks for uploadify
	 */
	protected $validCallbacks = array(
        'onCancel',
        'onClearQueue',
        'onDestroy',
        'onDialogClose',
        'onDialogOpen',
        'onDisable',
        'onEnable',
        'onFallback',
        'onInit',
        'onQueueComplete',
        'onSelect',
        'onSelectError',
        'onSWFReady',
        'onUploadComplete',
        'onUploadError',
        'onUploadProgress',
        'onUploadStart',
        'onUploadSuccess'
	);

	/**
	 * Make the code to be inserted in the view
	 */
	public function run()
	{
		$clientScript = Yii::app()->getClientScript();
		$dir = dirname(__FILE__);
		$this->baseUrl = Yii::app()->getAssetManager()->publish($dir);

		$clientScript->registerScriptFile($this->baseUrl . '/uploadify-v3.1/swfobject.js', CClientScript::POS_HEAD);
		$clientScript->registerScriptFile($this->baseUrl . '/uploadify-v3.1/jquery.uploadify-3.1.min.js', CClientScript::POS_HEAD);		
		$clientScript->registerCssFile($this->baseUrl . '/uploadify-v3.1/uploadify.css');

		list($name, $id) = $this->resolveNameID();
		$options = $this->makeOptions();
		$js =<<<EOP
$("#{$id}").uploadify({$options});
EOP;
		$clientScript->registerScript('Yii.'.get_class($this).'#'.$id, $js, CClientScript::POS_READY);

		$this->htmlOptions['id'] = $id;
		$this->htmlOptions['name'] = $id;
		$this->htmlOptions['type'] = 'file';
		$html = CHtml::tag('input', $this->htmlOptions, $this->body);
		echo $html;
	}

	/**
	 * From JUI
	 * Check callbacks against valid callbacks
	 * @param array $value user's callbacks
	 * @param array $validCallbacks valid callbacks
	 */
	protected static function checkCallbacks($value, $validCallbacks)
	{
		if (!empty($validCallbacks)) {
			foreach ($value as $key=>$val) {
				if (!in_array($key, $validCallbacks)) {
					throw new CException(Yii::t('EUploadify', '{k} must be one of: {c}', array('{k}'=>$key, '{c}'=>implode(', ', $validCallbacks))));
				}
			}
		}
	}

	/**
	 * From JUI extension
	 * Check the options against the valid ones
	 *
	 * @param array $value user's options
	 * @param array $validOptions valid options
	 */
	protected static function checkOptions($value, $validOptions)
	{
		if (!empty($validOptions)) {
			foreach ($value as $key=>$val) {

				if (!array_key_exists($key, $validOptions)) {
					throw new CException(Yii::t('EUploadify', '{k} is not a valid option', array('{k}'=>$key)));
				}
				$type = gettype($val);
				if ((!is_array($validOptions[$key]['type']) && ($type != $validOptions[$key]['type'])) || (is_array($validOptions[$key]['type']) && !in_array($type, $validOptions[$key]['type']))) {
					throw new CException(Yii::t('EUploadify', '{k} must be of type {t}',
					array('{k}'=>$key,'{t}'=>$validOptions[$key]['type'])));
				}
				if (array_key_exists('possibleValues', $validOptions[$key])) {
					if (!in_array($val, $validOptions[$key]['possibleValues'])) {
						throw new CException(Yii::t('EUploadify', '{k} must be one of: {v}', array('{k}'=>$key, '{v}'=>implode(', ', $validOptions[$key]['possibleValues']))));
					}
				}
				if (($type == 'array') && array_key_exists('elements', $validOptions[$key])) {
					self::checkOptions($val, $validOptions[$key]['elements']);
				}
				 
			}
		}
	}
	 
	/**
	 * Getter
	 * @return array
	 */
	public function getCallbacks()
	{
		return $this->callbacks;
	}
	 
	/**
	 * Setter
	 * @param array $value callbacks
	 */
	public function setCallbacks($value)
	{
		if (!is_array($value))
		throw new CException(Yii::t('EUploadify', 'callbacks must be an associative array'));
		self::checkCallbacks($value, $this->validCallbacks);
		$this->callbacks = $value;
	}
	 
	/**
	 * Getter
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Setter
	 * @param mixed $value
	 */
	public function setOptions($value)
	{
		if (!is_array($value))
		throw new CException(Yii::t('EUploadify', 'options must be an array'));
		self::checkOptions($value, $this->validOptions);
		$this->options = $value;
	}

	/**
	 * encode Options & Callbacks
	 * @return string
	 */
	protected function makeOptions()
	{
		// Set defaults
		if(!array_key_exists('swf', $this->options))
		$this->options['swf'] = $this->baseUrl . '/uploadify-v3.1/uploadify.swf?PHPSESSID=' . session_id();
		if(!array_key_exists('buttonText', $this->options))
		$this->options['buttonText'] = 'Browse';
		$this->options = array_merge($this->options, $this->callbacks);
		$encodedOptions = self::encode($this->options);
		return $encodedOptions;
	}

	/**
	 * Encode an array into a javascript array
	 *
	 * @param array $value
	 * @return string
	 */
	private static function encode($value)
	{
		$es=array();
		$n=count($value);
		if (($n)>0 && array_keys($value)!==range(0,$n-1)) {
			foreach($value as $k=>$v)
			{
				if(is_bool($v) || is_numeric($v) || substr($k,0,2) == "on")
				{
					if($v===true) $v = 'true';
					if($v===false) $v = 'false';
					$es[] = "'" . $k . "':" . $v ;
				}
				elseif($k == 'formData')
				{
					$sd ='';
					foreach($v as $dkey=>$dval)
					{
						$sd .= "'" . $dkey ."':'" . $dval ."',";
					}
					$sd = substr($sd, 0, -1);
					$es[] = "'" . $k . "':{" . $sd . "}";
				}  else
				$es[] = "'" . $k . "':'" .$v . "'";

			}

			return '{'.implode(',',$es).'}';
		} else {
			foreach($value as $v)
			{
				$es[] = "'" . $v . "'";
			}
			return '[' . implode(',',$es) . ']';
		}
	}
}