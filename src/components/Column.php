<?php

namespace rowe\dataupdown\components;

use yii\helpers\Inflector;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class Column extends BaseObject
{
    public $model;
    public $attribute;
    public $layout;
    public $value;
    public $header;
    public $footer;
    public $content;
    public $format = 'text';
    public $label;


    public function renderHeaderCell()
    {
        if ($this->header !== null || $this->label === null && $this->attribute === null) {
            return trim($this->header) !== '' ? $this->header : "";
        }

        $label = $this->getHeaderCellLabel();
        return $label;
    }


    /**
     * @inheritdoc
     * @since 2.0.8
     */
    protected function getHeaderCellLabel()
    {
        $models = $this->layout->models;

        if ($this->label === null) {
            if (($model = reset($models)) instanceof Model) {
                /* @var $model Model */
                $label = $model->getAttributeLabel($this->attribute);
            } else {
                $label = Inflector::camel2words($this->attribute);
            }
        } else {
            $label = $this->label;
        }

        return $label;
    }

    public function renderDataCell($model, $index)
    {
        if ($this->content === null) {
            return $this->layout->formatter->format($this->getDataCellValue($model, $index), $this->format);
        }

        return $this->layout->emptyCell;
    }

    public function renderFooterCell()
    {
        return trim($this->footer) !== '' ? $this->footer : "";
    }

    /**
     * Returns the data cell value.
     * @param mixed $model the data model
     * @param int $index the zero-based index of the data model among the models array returned by [[GridView::dataProvider]].
     * @return string the data cell value
     */
    public function getDataCellValue($model, $index)
    {
        if ($this->value !== null) {
            if (is_string($this->value)) {
                return ArrayHelper::getValue($model, $this->value);
            }

            return call_user_func($this->value, $model, $index, $this);
        } elseif ($this->attribute !== null) {
            return ArrayHelper::getValue($model, $this->attribute);
        }

        return null;
    }


}