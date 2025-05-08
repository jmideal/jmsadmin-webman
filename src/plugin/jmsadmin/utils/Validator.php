<?php

namespace plugin\jmsadmin\utils;

use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

class Validator extends Factory
{
    /**
     * @var $instance \Illuminate\Validation\Factory
     */
    private static $instance;

    private function __construct($translator = null)
    {
        parent::__construct($translator);
    }
    /***
     * 创建实例
     *
     * @return \Illuminate\Validation\Factory
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            $locale = config('translation.locale', 'zh_CN');
            $translationPath = config('translation.path', '');
            $translationFileLoader = new FileLoader(new Filesystem, $translationPath);
            $translator = new Translator($translationFileLoader, $locale);
            static::$instance = new Factory($translator);
            /*static::$instance->extend('mobile', function ($attribute, $value, $parameters, $validator) {
                return preg_match('/^1[3-9]\d{9}$/', $value) === 1;
            });
            static::$instance->replacer('mobile', function ($message, $attribute, $rule, $parameters) {
                print_r([$message, $attribute, $rule, $parameters]);
                $message = str_replace(':attribute', $attribute, $message);
                $message = str_replace(':val1', $parameters[0], $message);
                return str_replace(':val2', $parameters[1], $message);
            });*/
            $customValidator = config('plugin.jmsadmin.validator');
            foreach ($customValidator as $name => $conf) {
                if (!empty($conf['passes']) && $conf['passes'] instanceof Closure) {
                    static::$instance->extend($name, $conf['passes']);
                }
                if (!empty($conf['replacer']) && $conf['replacer'] instanceof Closure) {
                    static::$instance->replacer($name, $conf['replacer']);
                }
            }
        }
        return static::$instance;
    }
}