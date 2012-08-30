<?php

class GetId3_Tests_Autoloader
{
    /**
     * Registers GetId3_Tests_Autoloader as an SPL autoloader.
     */
    static public function register()
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    /**
     * Handles autoloading of classes.
     *
     * @param string $className A class name.
     */
    static public function autoload($className)
    {
        if (0 !== strpos($className, 'GetId3')) {
            return;
        }

        $realpath = realpath(dirname(__FILE__) . '/../');
        $realpath = explode(DIRECTORY_SEPARATOR, $realpath);
        array_pop($realpath);
        $realpath = implode(DIRECTORY_SEPARATOR, $realpath);
        $className = $realpath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, explode('_', $className)) . '.php';
        if (file_exists($className)) {
            require $className;
        }
    }
}

GetId3_Tests_Autoloader::register();
