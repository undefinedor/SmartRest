# SmartRest

## Purpose

The `controller` of ``yii\rest\urlRule`` must be set. So you have to set this property when you add  a controller. This project sloved it.


## Installation
The preferred way to install this extension is through [Composer](https://getcomposer.org/download/) .
```
composer require undefinedor/smart-rest
```


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
