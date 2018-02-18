The base library for extensions and apps
========================================

[![Latest Stable Version](https://poser.pugx.org/yiicod/yii2-base/v/stable)](https://packagist.org/packages/yiicod/yii2-base) [![Total Downloads](https://poser.pugx.org/yiicod/yii2-base/downloads)](https://packagist.org/packages/yiicod/yii2-base) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiicod/yii2-base/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiicod/yii2-base/?branch=master)[![Code Climate](https://codeclimate.com/github/yiicod/yii2-base/badges/gpa.svg)](https://codeclimate.com/github/yiicod/yii2-base)

#### Installation
Either run

```
php composer.phar require --prefer-dist yiicod/yii2-base "*"
```

or add

```json
"yiicod/yii2-base": "*"
```
#### Usage action
- yiicod\base\actions\base\Action. Methods: performAjaxValidation, loadModel

#### Usage model
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
#### Usage Enum
- Abstract class Enumerable for enum