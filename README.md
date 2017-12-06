# yii2-dataupdown
这是一个用于从数据库导出导入数据为指定格式的工具扩展

# Usage
```
$model = new CustomerSearch();
$dataProvider = $model->search(Yii::$app->request->queryParams);
$models = $dataProvider->query->all();
Yii::createObject([
    'class' => OfficeExporter::className(),
    'dataLayout' => Yii::createObject([
        'class' => DataLayout::className(),
        'models' => $models,
        'columns' => [
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d'],
            ],
            'capital',
            'company_name',
            'family_name',
            'given_name',

            'mobile',
            [
                'attribute' => 'date',
                'format' => ['date', 'php:Y-m-d'],
            ],
        ]
    ])
])->handle();
```

