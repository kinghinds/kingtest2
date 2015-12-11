<?php

class LanguageSelector extends CWidget {
    
    public function run() {

		$curLangLabel = 'zh_cn';
		$items = array();

        $languages = I18nHelper::getLanguages(false);
        foreach ($languages as $lang => $attr) {    
			array_push($items, array(
				'label' => $attr['label'],
				'link_url' => array_merge(array(''), $_GET, array('lang' => $lang)),
				'icon' => $attr['icon'],
				'lang' => $lang,
			));
        }
		if(isset($_GET['lang']) && $_GET['lang'] == 'en'){
			$curLangLabel = 'en';
		}
        $this->render('languageSelector', array(
			'curLangLabel' => $curLangLabel,
			'items' => $items,
		));
    }   
}

?>	