<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitded1f2bfc6fda7b77d1489391c846c51
{
    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'classes\\' => 8,
        ),
        'G' => 
        array (
            'Ginger\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'classes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
        'Ginger\\' => 
        array (
            0 => __DIR__ . '/..' . '/gingerpayments/ginger-php/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitded1f2bfc6fda7b77d1489391c846c51::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitded1f2bfc6fda7b77d1489391c846c51::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitded1f2bfc6fda7b77d1489391c846c51::$classMap;

        }, null, ClassLoader::class);
    }
}
