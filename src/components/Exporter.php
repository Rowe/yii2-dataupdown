<?php

namespace rowe\dataupdown\components;


use yii\base\BaseObject;
use yii\base\InvalidConfigException;

abstract class Exporter extends BaseObject
{

    /**
     * BaseDataLayout
     * @var array
     */
    public $dataLayout;

    public function init()
    {
        parent::init();
        if ($this->dataLayout === null) {
            throw new InvalidConfigException('the property \'$dataLayout\' must be set');
        } else if (!$this->dataLayout instanceof BaseDataLayout) {
            throw new InvalidConfigException('the property \'$dataLayout\' must be the instance of BaseDataLayout ');
        }
    }

    abstract public function handle();


}