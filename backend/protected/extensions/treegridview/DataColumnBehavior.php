<?php
class DataColumnBehavior extends CBehavior {
	public function renderCustomDataCell($row) {
		$data = $this->owner->grid->dataProvider->data[$row];
		$options = $this->owner->htmlOptions;
		if ($this->owner->cssClassExpression !== null) {
			$class = $this->owner->evaluateExpression(
					$this->owner->cssClassExpression,
					array(
							'row' => $row,
							'data' => $data
					));
			if (isset($options['class'])) {
				$options['class'] .= ' ' . $class;
			} else {
				$options['class'] = $class;
			}
		}
		echo CHtml::openTag('td', $options);
		$this->renderCustomDataCellContent($row, $data);
		echo '</td>';
	}
	
	protected function renderCustomDataCellContent($row, $data) {
		switch (get_class($this->owner)) {
			case 'CButtonColumn':
				$this->renderButtonColumnDataCellContent($row, $data);
				break;
			case 'CDataColumn';
				$this->renderDataColumnDataCellContent($row, $data);
				break;
		}
	}
	
	protected function renderButtonColumnDataCellContent($row, $data) {
		$tr = array();
		ob_start();
		foreach ($this->owner->buttons as $id => $button) {
			$this->owner->renderButton($id, $button, $row, $data);
			$tr['{' . $id . '}'] = ob_get_contents();
			ob_clean();
		}
		ob_end_clean();
		echo strtr($this->owner->template, $tr);
	}
	
	protected function renderDataColumnDataCellContent($row, $data) {
		if ($this->owner->value !== null) {
			$value = $this->owner->evaluateExpression($this->owner->value,
					array(
							'data' => $data,
							'row' => $row
					));
		} else if ($this->owner->name !== null) {
			$value = CHtml::value($data, $this->owner->name);
		}
		if ($value === null) {
			$value = $this->owner->grid->nullDisplay;
		} else {
			$value = $this->owner->grid->getFormatter()->format($value,
					$this->owner->type);
		}
		if ($this->owner->name == $this->owner->grid->titleFieldName) {
			$interval = CHtml::value($data, $this->owner->grid->layerFieldName)
					* $this->owner->grid->layerInterval;
			echo CHtml::tag('div',
					array(
							'style' => 'padding-left:' . $interval . 'px'
					), $value);
		} else {
			echo $value;
		}
	}
	
	protected function renderButton($id, $button, $row, $data) {
		if (isset($button['visible'])
				&& !$this->evaluateExpression($button['visible'],
						array(
								'row' => $row,
								'data' => $data
						))) {
			return;
		}
		$label = isset($button['label']) ? $button['label'] : $id;
		$url = isset($button['url']) ? $this->evaluateExpression(
				$button['url'],
				array(
						'data' => $data,
						'row' => $row
				)) : '#';
		$options = isset($button['options']) ? $button['options'] : array();
		if (!isset($options['title'])) {
			$options['title'] = $label;
		}
		if (isset($button['imageUrl']) && is_string($button['imageUrl'])) {
			echo CHtml::link(CHtml::image($button['imageUrl'], $label), $url,
					$options);
		} else {
			echo CHtml::link($label, $url, $options);
		}
	}
	
}
?>