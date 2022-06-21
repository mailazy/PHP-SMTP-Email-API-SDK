<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit18ded692cafc40150b78ef34c0c72e63
{
    public static $files = array (
        'f3c6cac7a6e9437da0dbaf876b532c43' => __DIR__ . '/../..' . '/src/request.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'M' => 
        array (
            'Mailazy\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Mailazy\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit18ded692cafc40150b78ef34c0c72e63::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit18ded692cafc40150b78ef34c0c72e63::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit18ded692cafc40150b78ef34c0c72e63::$classMap;

        }, null, ClassLoader::class);
    }
}