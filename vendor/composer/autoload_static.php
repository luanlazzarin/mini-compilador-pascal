<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit66360f5212906b969ef2061f2f6043a1
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit66360f5212906b969ef2061f2f6043a1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit66360f5212906b969ef2061f2f6043a1::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}