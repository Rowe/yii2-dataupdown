<?php

namespace rowe\dataupdown\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\base\Request;

class LinkExporter extends Widget
{

    public $model;

    public $options;

    public $route;

    public $label;

    public function init()
    {

    }

    public function run()
    {
        $this->renderExportLink();
    }


    protected function renderExportLink()
    {
        $request = Yii::$app->getRequest();
        $params = $request instanceof Request ? $request->getQueryParams() : [];
        $params[0] = $this->route === null ? Yii::$app->controller->getRoute() : $this->route;
        echo Html::a($this->label, Yii::$app->getUrlManager()->createUrl($params), $this->options);
    }

}