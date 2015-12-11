<?php

Yii::import('zii.widgets.grid.CGridView');
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DataColumnBehavior.php';

class TreeGridView extends CGridView {
	public $idFieldName = 'id';
	public $parentIdFieldName = 'parent_id';
	public $layerFieldName = 'layer';
	public $titleFieldName = 'title';
	public $layerInterval = 40;
	public $enablePagination = FALSE;
	
	/**
	 * Renders the table body.
	 */
	public function renderTableBody() {
		$data = $this->dataProvider->getData();
		$n = count($data);
		echo "<tbody>\n";
		if ($n > 0) {
			for ($row = 0; $row < $n; ++$row) {
				if (isset($data[$row][$this->idFieldName])
						&& isset($data[$row][$this->parentIdFieldName])) {
					$this->renderTableRowWidthId($row,
							$data[$row][$this->idFieldName],
							$data[$row][$this->parentIdFieldName]);
				} else {
					$this->renderTableRow($row);
				}
			}
		} else {
			echo '<tr><td colspan="' . count($this->columns) . '">';
			$this->renderEmptyText();
			echo "</td></tr>\n";
		}
		echo "</tbody>\n";
	}
	
	/**
	 * Renders a table body row.
	 * @param integer $row the row number (zero-based).
	 * @param integer $id the id number.
	 * @param integer $parentId the parentId number.
	 */
	public function renderTableRowWidthId($row, $id, $parentId) {
		if ($this->rowCssClassExpression !== null) {
			$data = $this->dataProvider->data[$row];
			echo '<tr id="node-' . $id . '" class="'
					. ($parentId > 0 ? 'child-of-node-' . $parentId . ' ' : null)
					. ($this->evaluateExpression($this->rowCssClassExpression,
							array(
									'row' => $row,
									'data' => $data
							))) . '">';
		} else if (is_array($this->rowCssClass)
				&& ($n = count($this->rowCssClass)) > 0) {
			echo '<tr id="node-' . $id . '" class="'
					. ($parentId > 0 ? 'child-of-node-' . $parentId . ' ' : null)
					. $this->rowCssClass[$row % $n] . '">';
		} else {
			echo '<tr id="node-' . $id . '"'
					. ($parentId > 0 ? 'class=child-of-node-' . $parentId . '"' : null)
					. '>';
		}
		
		foreach ($this->columns as $column) {
			$column->attachBehavior('DataColumn', new DataColumnBehavior());
			$column->renderCustomDataCell($row);
		}
		
		echo "</tr>\n";
	}
	
	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript() {
		$id = $this->getId();
		
		if ($this->ajaxUpdate === false)
			$ajaxUpdate = false;
		else
			$ajaxUpdate = array_unique(
					preg_split('/\s*,\s*/', $this->ajaxUpdate . ',' . $id, -1,
							PREG_SPLIT_NO_EMPTY));
		$options = array(
				'ajaxUpdate' => $ajaxUpdate,
				'ajaxVar' => $this->ajaxVar,
				'pagerClass' => $this->pagerCssClass,
				'loadingClass' => $this->loadingCssClass,
				'filterClass' => $this->filterCssClass,
				'tableClass' => $this->itemsCssClass,
				'selectableRows' => $this->selectableRows,
		);
		if ($this->ajaxUrl !== null)
			$options['url'] = CHtml::normalizeUrl($this->ajaxUrl);
		if ($this->updateSelector !== null)
			$options['updateSelector'] = $this->updateSelector;
		if ($this->enablePagination)
			$options['pageVar'] = $this->dataProvider->getPagination()->pageVar;
		if ($this->beforeAjaxUpdate !== null)
			$options['beforeAjaxUpdate'] = (strpos($this->beforeAjaxUpdate,
					'js:') !== 0 ? 'js:' : '') . $this->beforeAjaxUpdate;
		if ($this->afterAjaxUpdate !== null)
			$options['afterAjaxUpdate'] = (strpos($this->afterAjaxUpdate, 'js:')
					!== 0 ? 'js:' : '') . $this->afterAjaxUpdate;
		if ($this->ajaxUpdateError !== null)
			$options['ajaxUpdateError'] = (strpos($this->ajaxUpdateError, 'js:')
					!== 0 ? 'js:' : '') . $this->ajaxUpdateError;
		if ($this->selectionChanged !== null)
			$options['selectionChanged'] = (strpos($this->selectionChanged,
					'js:') !== 0 ? 'js:' : '') . $this->selectionChanged;
		
		$options = CJavaScript::encode($options);
		
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerCoreScript('bbq');
		
		$gridViewPath = Yii::app()->basePath
				. '/extensions/treegridview/gridView/';
		$gridViewBaseUrl = Yii::app()->getAssetManager()->publish($gridViewPath);
		$cs->registerScriptFile($gridViewBaseUrl . '/jquery.yiigridview.js',
				CClientScript::POS_END);
		$cs->registerScript(__CLASS__ . '#' . $id,
				"jQuery('#$id').yiiGridView($options);");
		
		$treeTablePath = Yii::app()->basePath
				. '/extensions/treegridview/treeTable/src/';
		$treeTableBaseUrl = Yii::app()->getAssetManager()->publish(
				$treeTablePath);
		$cs->registerCssFile(
				$treeTableBaseUrl . '/stylesheets/jquery.treeTable.css');
		$cs->registerScriptFile(
				$treeTableBaseUrl . '/javascripts/jquery.treeTable.min.js');
		$cs->registerScriptFile(
				$treeTableBaseUrl . '/javascripts/jquery.treeTableExt.js');
		$cs->registerScript(__CLASS__ . '#' . $id . '#treeTable',
				"jQuery('#$id').find('.items').treeTable({'initialState':'expanded'});");
	}
}

?>
