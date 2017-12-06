<?php

namespace rowe\dataupdown\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\i18n\Formatter;

class DataLayout extends BaseDataLayout
{
    public $formatter;
    public $columns = [];
    public $emptyCell = '';
    public $showHeader = true;
    public $showFooter = false;

    public function init()
    {
        parent::init();
        if ($this->formatter === null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }

        $this->initColumns();
    }


    /**
     * Creates column objects and initializes them.
     */
    protected function initColumns()
    {

        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = Yii::createObject(array_merge([
                    'class' => Column::className(),
                    'layout' => $this,
                ], $column));
            }

            $this->columns[$i] = $column;
        }
    }

    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        return Yii::createObject([
            'class' => Column::className(),
            'layout' => $this,
            'attribute' => $matches[1],
            'format' => isset($matches[3]) ? $matches[3] : 'text',
            'label' => isset($matches[5]) ? $matches[5] : null,
        ]);
    }

    public function renderItems()
    {
        $header = $this->showHeader ? $this->renderHeader() : [];
        $body = $this->renderBody();
        $footer = $this->showFooter ? $this->renderFooter() : [];

        return array_merge([$header], $body, [$footer]);
    }

    public function renderHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        return $cells;
    }

    public function renderBody()
    {
        $models = $this->models;
        $rows = [];
        foreach ($models as $index => $model) {
            $rows[] = $this->renderRow($model, $index);
        }
        return $rows;
    }

    public function renderRow($model, $index)
    {
        $cells = [];
        /* @var $column Column */
        foreach ($this->columns as $column) {
            $cells[] = $column->renderDataCell($model, $index);
        }

        return $cells;
    }

    public function renderFooter()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderFooterCell();
        }
        return $cells;
    }
}