<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit0080d6acc2b8cd2c5f49ed38a37612fa
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit0080d6acc2b8cd2c5f49ed38a37612fa', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit0080d6acc2b8cd2c5f49ed38a37612fa', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit0080d6acc2b8cd2c5f49ed38a37612fa::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}