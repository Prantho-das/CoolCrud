<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5fe216a8e1b7257340a75247153d4160
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Prantho\\Crud\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Prantho\\Crud\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Prantho\\Crud\\Console\\CrudCommand' => __DIR__ . '/../..' . '/src/Console/CrudCommand.php',
        'Prantho\\Crud\\CrudServiceProvider' => __DIR__ . '/../..' . '/src/CrudServiceProvider.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5fe216a8e1b7257340a75247153d4160::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5fe216a8e1b7257340a75247153d4160::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5fe216a8e1b7257340a75247153d4160::$classMap;

        }, null, ClassLoader::class);
    }
}
