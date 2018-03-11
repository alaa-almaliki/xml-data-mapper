<?php

final class Autoload
{
    /** @var  Autoloader */
    private static $_instance;
    /**
     * @return Autoloader|static
     */
    private static function _instance()
    {
        if (!self::$_instance) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }
    public static function register()
    {
        spl_autoload_register([self::_instance(), '_autoload']);
    }
    /**
     * @param string $class
     */
    private static function _autoload($class)
    {
        $file = __DIR__ . '/src' . DIRECTORY_SEPARATOR . $class . '.php';
        if (!file_exists($file)) {
            $file = __DIR__ . '/src/Order' . DIRECTORY_SEPARATOR . $class . '.php';
        }

        if (strpos($class, '\\') !== false) {
            if (!file_exists($file)) {
                $classPath = explode('\\', $class);
                unset($classPath[0]);
                unset($classPath[1]);
                $file = dirname(dirname(__FILE__))  . '/src'.DIRECTORY_SEPARATOR . implode('/', $classPath) . '.php';
            }
        }

        require_once $file;
    }

}

Autoload::register();