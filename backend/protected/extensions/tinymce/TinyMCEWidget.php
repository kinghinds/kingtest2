<?php

Yii::import('zii.widgets.jui.CJuiInputWidget');

class TinyMCEWidget extends CJuiInputWidget {

	const DEFAULT_WIDTH = '100%';
	const DEFAULT_HEIGHT = '400';
	const DEFAULT_CLASS = 'tinymce-editor';
	const TINYMCE_VERSION = '3.5.7';
	const TINYMCE_COMPRESSOR_VERSION = '2.0.4';
	const KCFINDER_VERSION = '2.51';

	protected $language;
	protected $width;
	protected $height;
	protected $class;

	public function getLanguage($language) {
		$languages = array('zh-cn', 'en');
		return in_array($language, $languages) ? $language : reset($languages);
	}

	public function getClass($class) {
		$arr = explode(' ', $class);
		if (in_array(self::DEFAULT_CLASS, $arr) == false) {
			array_push($arr, self::DEFAULT_CLASS);
		}
		$this->class = implode(' ', $arr);
		$this->htmlOptions['class'] = $this->class;
	}

	public function run() {
		list($name, $id) = $this->resolveNameID();

		if (isset($this->htmlOptions['id'])) {
			$id = $this->htmlOptions['id'];
		} else {
			$this->htmlOptions['id'] = $id;
		}

		if (isset($this->htmlOptions['name'])) {
			$name = $this->htmlOptions['name'];
		} else {
			$this->htmlOptions['name'] = $name;
		}

		if (isset($this->htmlOptions['language'])) {
			$this->language = $this->getLanguage($this->htmlOptions['language']);
		} else {
			$this->language = $this->getLanguage(null);
		}

		if (isset($this->htmlOptions['width'])) {
			$this->width = $this->htmlOptions['width'];
		} else {
			$this->width = self::DEFAULT_WIDTH;
		}

		if (isset($this->htmlOptions['height'])) {
			$this->height = $this->htmlOptions['height'];
		} else {
			$this->height = self::DEFAULT_HEIGHT;
		}

		if (isset($this->htmlOptions['class'])) {
			$this->class = $this->getClass($this->htmlOptions['class']);
		} else {
			$this->class = self::DEFAULT_CLASS;
		}
		$this->htmlOptions['class'] = $this->class;

		if ($this->hasModel()) {
			echo CHtml::activeTextArea($this->model, $this->attribute,
					$this->htmlOptions);
		} else {
			echo CHtml::textArea($name, $this->value, $this->htmlOptions);
		}

		$basePath = Yii::app()->basePath . '/extensions/tinymce/';
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath);
		$editorUrl = $baseUrl . '/tiny_mce_' . self::TINYMCE_VERSION;
		$contentCssUrl = Helper::mediaUrl('inc/layout_backend.css', FRONTEND);
		$kcfinderUrl = $baseUrl . '/kcfinder-' . self::KCFINDER_VERSION;
		
		
		// 设置 KCFinder 图片默认排序
		if (isset($_COOKIES['KCFINDER_order']) == false)
			setcookie('KCFINDER_order', 'date', time() + (86400 * 30));
		if (isset($_COOKIES['KCFINDER_orderDesc']) == false)
			setcookie('KCFINDER_orderDesc', 'on', time() + (86400 * 30));


		Yii::app()->clientScript->registerCoreScript('jquery');
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile($editorUrl . '/jquery.tinymce.js');
		$cs->registerScriptFile($editorUrl . '/tiny_mce_gzip.js');
		
		// TinyMCE PHP Compressor Config
		
		$js = <<<EOP
tinyMCE_GZ.init({
	mode : 'exact',
	editor_selector : '{$this->class}',
	themes : 'advanced',
	plugins : 'autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist',
	languages : '{$this->language}',
	disk_cache : true,
	debug : false
});
EOP;
		$cs->registerScript(__CLASS__ . '_TinyMCE_Compressor', $js);

		// TinyMCE Config
		$js = <<<EOP
$('.{$this->class}').tinymce({
	script_url : '{$editorUrl}/tiny_mce.js',
	language : '{$this->language}',
	theme : 'advanced',
	plugins : 'autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist',
	width : '{$this->width}',
	height : '{$this->height}',
	theme_advanced_buttons1 : 'newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect',
	theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,|,insertdate,inserttime,|,forecolor,backcolor',
	theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,ltr,rtl',
	theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,fullscreen,preview,code',
	theme_advanced_toolbar_location : 'top',
	theme_advanced_toolbar_align : 'left',
	theme_advanced_statusbar_location : 'bottom',
	theme_advanced_resizing : true,
	theme_advanced_fonts : '宋体=宋体;黑体=黑体;楷书=楷书;幼圆=幼圆;Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;;Impact=impact,chicago;;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats;',
	keep_styles : true,
	convert_fonts_to_spans : false,
	convert_newlines_to_brs : false,
	// verify_css_classes : false,
	// verify_html : false,
	// forced_root_block : false,
	valid_elements : '*[*],img[class|src|border|alt=|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style]',
	extended_valid_elements : '*[*],script[charset|defer|language|src|type]',
	convert_urls : false,
	file_browser_callback : openKcFinder,
	content_css : '{$contentCssUrl}'
});
EOP;
		$cs->registerScript(__CLASS__ . '_TinyMCE', $js);

		// KCFinder Config
		$js = <<<EOP
function openKcFinder(field_name, url, type, win){
	tinyMCE.activeEditor.windowManager.open({
		file : '{$kcfinderUrl}/browse.php?opener=tinymce&lang={$this->language}&type=' + type,
		title : 'KCFinder',
		width : 700,
		height : 500,
		resizable : 'yes',
		inline : true,
		close_previous : 'no',
		popup_css : false
	},{
		window : win,
		input : field_name
	});
}
EOP;
		$cs->registerScript(__CLASS__ . '_KCFinder', $js);
	}
}

?>