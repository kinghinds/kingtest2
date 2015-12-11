<?php

Yii::import('zii.widgets.CListView');
Yii::import('ext.NPager.NPager');

class NListView extends CListView
{

    public $pagerlist = array(
        '10' => 10,
        '25' => 25,
        '50' => 50,
        '100' => 100,
        'all' => 'ALL',
    );
    public $pagerlistCssClass = 'pager-list';
    public $template = "{summary}\n{pagerlist}\n{pager}\n{items}";
    public $textItemsPerPage = 'items per pages';

    public function init()
    {
        parent::init();
    }

    public function renderPagerList()
    {
        if (!$this->enablePagination)
            return;

        if (isset($this->pagerlist['all']))
        {
            $t = $this->pagerlist;
            $this->pagerlist = array();
            foreach ($t as $k => $v)
                if ($k == 'all')
                    $this->pagerlist[$this->dataProvider->getTotalItemCount()] = Yii::t('zii', $v);
                else
                    $this->pagerlist[$k] = $v;
        }
        $controller = $this->getController();
        $route = $controller->route;
        $parameters = $_GET;
        unset($parameters['pageSize']);
        $parameters['page'] = $this->dataProvider->getPagination()->getCurrentPage() + 1;
        $parameters['pageSize'] = '';
        $url = $controller->createUrl($route, $parameters);

        $id = parent::getId();

        echo '<div class="' . $this->pagerlistCssClass . '">';
        echo CHtml::dropDownList($id . '_pagerlist', $this->dataProvider->pagination->pageSize, $this->pagerlist, array('onchange' => $id . "_changePageSize(\"$id\",\"$url\")"));
        echo '<span>' . Yii::t('zii', $this->textItemsPerPage) . '</span>';
        echo '</div>';
    }

    public function registerClientScript()
    {
        if (!Yii::app()->request->isAjaxRequest)
        {
            parent::registerClientScript();

            $cs = Yii::app()->clientScript;

            $basePath = Yii::getPathOfAlias('ext.NPager.assets');
            $baseUrl = Yii::app()->getAssetManager()->publish($basePath);

            $id = parent::getId();
            $cs = Yii::app()->clientScript;
            $cs->registerCoreScript('jquery');

            $js = "function $id" . "_changePageSize(id,url)\n";
            $js.="{\n";
            $js.="    url = {url:url+$('#'+id+'>.$this->pagerlistCssClass>select').val()};\n";
            $js.="    $.fn.yiiListView.update(id,url);\n";
            $js.="}\n";
            $js.="\n";
            $cs->registerScript($this->getId(), $js, CClientScript::POS_END);
            $cs->registerCssFile($baseUrl . '/style.css');
        }
    }

}

?>
