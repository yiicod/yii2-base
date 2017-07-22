Common thinks
=============

This is base library for extensions and apps:

Installation
------------
Either run

```
php composer.phar require --prefer-dist yiicod/yii2-base "*"
```

or add

```json
"yiicod/yii2-base": "*"
```
Usage action
------------
- yiicod\base\actions\base\Action. Methods: performAjaxValidation, loadModel

Usage model
-----------
```php
    /**
     * Attributes mapper(Dynamic attr by mapping).
     * yiicod\base\models\behaviors\AttributesMapBehavior
     * $model->title // But in db it can be title_ext
     * $model->alias // But in db it can be slug
     */
    [
        'class' => 'yiicod\base\models\behaviors\AttributesMapBehavior',
        'attributesMap' => [
            'fieldTitle' => 'title_ext',
            'fieldAlias' => 'slug',
        ]
    ],
    /**
     * HTMLPurify
     */
    [
        'class' => 'yiicod\base\models\behaviors\XssBehavior',
    ]
```
Usage Enum
----------
- Abstract class Enumerable for enum