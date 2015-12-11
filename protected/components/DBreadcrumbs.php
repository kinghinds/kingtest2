<?php

Yii::import('zii.widgets.CBreadcrumbs');

class DBreadcrumbs extends CBreadcrumbs {
	public $lastCssClass = 'last';

	public function run()
	{
		if(empty($this->links))
			return;

		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
		$links=array();
		if($this->homeLink===null)
			$links[]=CHtml::link(Yii::t('zii','Home'),Yii::app()->homeUrl);
		else if($this->homeLink!==false)
			$links[]=$this->homeLink;

		$i = 1;
		foreach($this->links as $label=>$url)
		{
			if(is_string($label) || is_array($url))
			{
				if (count($this->links) == $i) 
				{
					$links[]=CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url, array('class' => $this->lastCssClass));
				} 
				else 
				{
					$links[]=CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url);
				}
			}
			else
			{
				if (count($this->links) == $i) 
				{
					$links[]='<span class="' . $this->lastCssClass . '">'.($this->encodeLabel ? CHtml::encode($url) : $url).'</span>';
				}
				else
				{
					$links[]='<span>'.($this->encodeLabel ? CHtml::encode($url) : $url).'</span>';
				}
			}
			$i++;
		}
		echo implode($this->separator,$links);
		echo CHtml::closeTag($this->tagName);
	}
}

?>