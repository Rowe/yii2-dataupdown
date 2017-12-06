<?php

namespace rowe\dataupdown\components;


use yii\base\Component;
use yii\base\InvalidConfigException;

abstract class BaseDataLayout extends Component
{

    public $models = [];

    public function init()
    {
        parent::init();
        if ($this->models === null) {
            throw new InvalidConfigException('the models must be set');
        }
    }

    abstract public function renderItems();

    public function render()
    {
       return $this->renderItems();
    }

}