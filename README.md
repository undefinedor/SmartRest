# SmartRest

## Purpose

The `controller` of ``yii\rest\urlRule`` must be set. So you have to set this property when you add  a controller. This project sloved it.


## Usage

```php

 'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                ['class' => 'SmartRest\UrlRule'],
            ],
        ],

```
