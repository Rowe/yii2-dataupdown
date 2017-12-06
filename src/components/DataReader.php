<?php

namespace rowe\dataupdown\components;


use yii\base\BaseObject;
use yii\base\InvalidConfigException;

abstract class DataReader extends BaseObject
{


    public $source;

    private $_data;


    public function init()
    {
        parent::init();
        if ($this->source === null) {
            throw new InvalidConfigException('the source property must be set');
        }

        if (!file_exists($this->source)) {
            throw new InvalidConfigException('the source file is not exist');
        }
        $this->load();
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }


    /**
     *
     */
    public function load()
    {
        $data = $this->loadData();
        $this->setData($data);
    }

    abstract function loadData();


}