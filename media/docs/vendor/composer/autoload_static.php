<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit142044c9488f84543b645b259a0e5cfe
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit142044c9488f84543b645b259a0e5cfe::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit142044c9488f84543b645b259a0e5cfe::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
