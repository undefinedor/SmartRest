<?php
namespace SmartRest;

use yii\base\Application;
use yii\base\Module;
use yii\helpers\Inflector;
use yii\rest\Controller;
use yii\web\CompositeUrlRule;

class UrlRule extends \yii\rest\UrlRule
{
    public function init()
    {

        if (empty($this->controller)) {
            $this->controller = $this->getControllers(\Yii::$app);
        }

        $controllers = [];
        foreach ((array) $this->controller as $urlName => $controller) {
            if (is_int($urlName)) {
                $urlName = $this->pluralize ? Inflector::pluralize($controller) : $controller;
            }
            $controllers[$urlName] = $controller;
        }

        $this->controller = $controllers;

        $this->prefix = trim($this->prefix, '/');

        CompositeUrlRule::init();
    }

    /**
     * @param Module $module
     *
     * @return array
     */
    public function getControllers($module)
    {
        $prefix = $module instanceof Application ? '' : $module->getUniqueId() . '/';

        $controllers = [];
        foreach (array_keys($module->controllerMap) as $id) {
            $controllers[] = $prefix . $id;
        }


        foreach ($module->getModules() as $id => $child) {
            $child = $module->getModule($id);
            if ($child === null || !($child instanceof \app\url\Module)) {
                continue;
            }
            foreach ($this->getControllers($child) as $command) {
                $controllers[] = $command;
            }
        }

        if (is_dir($module->controllerPath)) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($module->controllerPath, \RecursiveDirectoryIterator::KEY_AS_PATHNAME));
            $iterator = new \RegexIterator($iterator, '/.*Controller\.php$/', \RecursiveRegexIterator::GET_MATCH);
            foreach ($iterator as $matches) {
                $file = $matches[0];

                $relativePath = str_replace($module->controllerPath, '', $file);
                $class = strtr($relativePath, [
                    '/' => '\\',
                    '.php' => '',
                ]);
                $controllerClass = $module->controllerNamespace . $class;

                if ($this->validateControllerClass($controllerClass)) {
                    $dir = ltrim(pathinfo($relativePath, PATHINFO_DIRNAME), '\\/');

                    $controller = Inflector::camel2id(substr(basename($file), 0, -14), '-', true);
                    if (!empty($dir)) {
                        $controller = $dir . '/' . $controller;
                    }

                    $controllers[]=$controller;
                }
            }
        }

        return $controllers;
    }

    /**
     * @param $controllerClass
     *
     * @return bool
     * @throws \ReflectionException
     */
    protected function validateControllerClass($controllerClass)
    {
        if (class_exists($controllerClass)) {
            $class = new \ReflectionClass($controllerClass);
            return !$class->isAbstract() && $class->isSubclassOf(Controller::class);
        }

        return false;
    }
}