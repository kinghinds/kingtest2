<?php

Yii::import('zii.widgets.jui.CJuiInputWidget');

class TinyMCEWidget extends CJuiInputWidget {
	const TAB = "\t";
	const BR = "\r\n";
	const DEFAULT_WIDTH = '100%';
	const DEFAULT_HEIGHT = '400';
	const DEFAULT_CLASS = 'tinymce-editor';

	protected $language;
	protected $width;
	protected $height;
	protected $class;

	public function getLanguage() {
		return $this->language;
	}

	public function setLanguage($language) {
		$languages = array('zh-cn', 'en');
		if (in_array($language, $languages)) {
			$this->language = $language;
		} else {
			$this->language = reset($languages);
			//$this->language = current($languages);
		}
	}

	public function getWidth() {
		return $this->width;
	}

	public function setWidth($width) {
		$this->width = $width;
		//$this->htmlOptions['width'] = $this->width;
	}

	public function getHeight() {
		return $this->height;
	}

	public function setHeight($height) {
		$this->height = $height;
		//$this->htmlOptions['height'] = $this->height;
	}

	public function getClass() {
		return $this->class;
	}

	public function setClass($class) {
		$arr = explode(" ", $class);
		if (in_array(self::DEFAULT_CLASS, $arr) == false) {
			array_push($arr, self::DEFAULT_CLASS);
		}
		$this->class = implode(" ", $arr);
		$this->htmlOptions['class'] = $this->class;
	}

	public function run() {
		list($name, $id) = $this->resolveNameID();

		if (isset($this->htmlOptions['id']))
			$id = $this->htmlOptions['id'];
		else
			$this->htmlOptions['id'] = $id;

		if (isset($this->htmlOptions['name']))
			$name = $this->htmlOptions['name'];
		else
			$this->htmlOptions['name'] = $name;

		if (isset($this->htmlOptions['language']))
			$this->setLanguage($this->htmlOptions['language']);
		else
			$this->setLanguage(null);

		if (isset($this->htmlOptions['width']))
			$this->setWidth($this->htmlOptions['width']);
		else
			$this->setWidth(self::DEFAULT_WIDTH);

		if (isset($this->htmlOptions['height']))
			$this->setHeight($this->htmlOptions['height']);
		else
			$this->setHeight(self::DEFAULT_HEIGHT);

		if (isset($this->htmlOptions['class']))
			$this->setClass($this->htmlOptions['class']);
		else
			$this->setClass(self::DEFAULT_CLASS);

		if ($this->hasModel())
			echo CHtml::activeTextArea($this->model, $this->attribute,
					$this->htmlOptions);
		else
			echo CHtml::textArea($name, $this->value, $this->htmlOptions);

		$editorPath = Yii::app()->basePath
				. '/extensions/tinymce/tiny_mce_3.4.9';
		$editorBaseUrl = Yii::app()->getAssetManager()->publish($editorPath);

		Yii::app()->clientScript->registerCoreScript('jquery');
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile($editorBaseUrl . '/jquery.tinymce.js');
		//$cs->registerScriptFile($editorBaseUrl . '/file_browser.js');
		$cs->registerScriptFile($editorBaseUrl . '/tiny_mce_gzip.js');

		/*
		$js = "tinyMCE_GZ.init({" . self::BR;
		$js .= self::TAB . "mode : 'exact'," . self::BR;
		//$js .= self::TAB . "elements : '" . $id . "'," . self::BR;
		$js .= self::TAB . "editor_selector : '" . $this->getClass() . "',"
				. self::BR;
		$js .= self::TAB . "themes : 'advanced'," . self::BR;
		$js .= self::TAB
				. "plugins : 'autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist',"
				. self::BR;
		$js .= self::TAB . "languages : '" . $this->getLanguage() . "',"
				. self::BR;
		$js .= self::TAB . "disk_cache : true," . self::BR;
		$js .= self::TAB . "debug : false" . self::BR;
		$js .= "});" . self::BR;
		*/

		$js = '';
		//$js .= "jQuery('#" . $id . "').tinymce({" . self::BR;
		$js .= "$('." . self::DEFAULT_CLASS . "').tinymce({" . self::BR;
		// Location of TinyMCE script
		$js .= self::TAB . "script_url : '" . $editorBaseUrl . "/tiny_mce.js',"
				. self::BR;

		// General options
		$js .= self::TAB . "language : '" . $this->getLanguage() . "',"
				. self::BR;
		$js .= self::TAB . "theme : 'advanced'," . self::BR;
		$js .= self::TAB
				. "plugins : 'autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist',"
				. self::BR;
		$js .= self::TAB . "width : '" . $this->getWidth() . "'," . self::BR;
		$js .= self::TAB . "height : '" . $this->getHeight() . "'," . self::BR;

		// Theme options
		$js .= self::TAB
				. "theme_advanced_buttons1 : 'newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect',"
				. self::BR;
		$js .= self::TAB
				. "theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,|,insertdate,inserttime,|,forecolor,backcolor',"
				. self::BR;
		$js .= self::TAB
				. "theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,ltr,rtl',"
				. self::BR;
		$js .= self::TAB
				. "theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,fullscreen,preview,code',"
				. self::BR;
		$js .= self::TAB . "theme_advanced_toolbar_location : 'top',"
				. self::BR;
		$js .= self::TAB . "theme_advanced_toolbar_align : 'left'," . self::BR;
		$js .= self::TAB . "theme_advanced_statusbar_location : 'bottom',"
				. self::BR;
		$js .= self::TAB . "theme_advanced_resizing : true," . self::BR;
		$js .= self::TAB
				. "theme_advanced_fonts : '宋体=宋体;黑体=黑体;楷书=楷书;幼圆=幼圆;Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;;Impact=impact,chicago;;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats;',"
				. self::BR;

		// Cleanup/Output
		$js .= self::TAB . "keep_styles : true," . self::BR;
		$js .= self::TAB . "convert_fonts_to_spans : false," . self::BR;
		$js .= self::TAB . "convert_newlines_to_brs : false," . self::BR;
		$js .= self::TAB . "valid_children : false," . self::BR;
		$js .= self::TAB . "valid_elements : false," . self::BR;
		$js .= self::TAB . "verify_css_classes : false," . self::BR;
		//$js .= self::TAB . "verify_html : false," . self::BR;
		$js .= self::TAB . "forced_root_block : false," . self::BR;

		// URL
		$js .= self::TAB . "convert_urls : false," . self::BR;

		// Image Upload
		$js .= self::TAB . "image_upload_url : '"
				. Yii::app()->createUrl('site/tinymceImageUpload')
				. "&editor_base_url=" . $editorBaseUrl . "'," . self::BR;

		// Content Css
		$js .= self::TAB . "content_css : '"
				. Helper::mediaUrl('inc/layout_backend.css', FRONTEND)
				. "'" . self::BR;

		$js .= "});" . self::BR;
		//$cs->registerScript(__CLASS__ . '#' . $id, $js);
		$cs->registerScript(__CLASS__, $js);
	}
}

?>