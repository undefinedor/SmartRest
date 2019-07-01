# SmartRest

## 目的

官方提供的`yii\rest\urlRule`类必须声明controller属性。每次增加控制器的时候,都需要在`controller`属性中声明,过于繁琐。本项目解决了该问题。


## 安装
最佳方式是通过[Composer](https://getcomposer.org/download/) .

```
composer require undefinedor/smart-rest
```


## 使用方法

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
