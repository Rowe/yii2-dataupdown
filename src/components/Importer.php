<?php

namespace rowe\dataupdown\components;


use yii\base\Component;
use yii\base\InvalidConfigException;
use Yii;
use yii\db\BaseActiveRecord;

class Importer extends Component
{
    public $modelClass;
    public $dataReaderClass;

    /**
     *  将数据源数据读取成二维数组行列形式，每行每列以健值对 ['标题'=>'值']表示
     * @var
     */
    public $rules;
    public $sourcePath;

    private $_model;
    private $_dataReader;

    public function init()
    {
        parent::init();
        if ($this->modelClass === null || $this->dataReaderClass === null || $this->sourcePath === null) {
            throw new InvalidConfigException('The properties modelClass, dataReaderClass, sourcePath must be set');
        } else if (!file_exists($this->sourcePath)) {
            throw new InvalidConfigException('The file of property sourcePath does not exist');
        } else if ($this->rules !== null) {
            $model = $this->getModel();
            if (!$model instanceof BaseActiveRecord) {
                throw new InvalidConfigException('The model class must be the instance of BaseActiveRecord');
            }
            foreach ($this->rules as $attribute => $title) {
                if (!$model->hasAttribute($attribute)) {
                    throw new InvalidConfigException('The attribute {$attribute} dose not belong to the model');
                }
            }
        }
    }

    public function save()
    {
        $importData = $this->getDataReader()->getData();

        foreach ($importData as $row) {
            $model = new $this->modelClass;
            foreach ($row as $title => $col) {
                $attributeName = array_search($title, $this->rules);
                if ($attributeName) {
                    $model->setAttribute($attributeName, $col);
                }
            }
            $model->save();
            print_r('<pre>');
            print_r($model->getErrors());
        }
    }


    public function getModel()
    {
        if ($this->_model === null) {
            return Yii::createObject([
                'class' => $this->modelClass
            ]);
        } else return $this->_model;
    }

    public function getDataReader()
    {
        if ($this->_dataReader === null) {
            return Yii::createObject([
                'class' => $this->dataReaderClass,
                'source' => $this->sourcePath,
            ]);
        } else return $this->_dataReader;
    }


}