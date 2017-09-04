<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbe64377ca94113cedb028d3495f37da2
{
    public static $files = array (
        '998c9b470de4a405c9039564fc4d1ef5' => __DIR__ . '/../..' . '/config.php',
    );

    public static $prefixLengthsPsr4 = array (
        'n' => 
        array (
            'newsapp\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'newsapp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'ComposerAutoloaderInitbe64377ca94113cedb028d3495f37da2' => __DIR__ . '/..' . '/composer/autoload_real.php',
        'Composer\\Autoload\\ClassLoader' => __DIR__ . '/..' . '/composer/ClassLoader.php',
        'Composer\\Autoload\\ComposerStaticInitbe64377ca94113cedb028d3495f37da2' => __DIR__ . '/..' . '/composer/autoload_static.php',
        'newsapp\\controllers\\AccountController' => __DIR__ . '/../..' . '/controllers/AccountController.php',
        'newsapp\\controllers\\CommentsController' => __DIR__ . '/../..' . '/controllers/CommentsController.php',
        'newsapp\\controllers\\HomeController' => __DIR__ . '/../..' . '/controllers/HomeController.php',
        'newsapp\\core\\App' => __DIR__ . '/../..' . '/core/App.php',
        'newsapp\\core\\Control' => __DIR__ . '/../..' . '/core/Control.php',
        'newsapp\\core\\Controller' => __DIR__ . '/../..' . '/core/Controller.php',
        'newsapp\\core\\Request' => __DIR__ . '/../..' . '/core/Request.php',
        'newsapp\\core\\Router' => __DIR__ . '/../..' . '/core/Router.php',
        'newsapp\\core\\database\\Connection' => __DIR__ . '/../..' . '/core/database/Connection.php',
        'newsapp\\core\\database\\QueryBuilder' => __DIR__ . '/../..' . '/core/database/QueryBuilder.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbe64377ca94113cedb028d3495f37da2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbe64377ca94113cedb028d3495f37da2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbe64377ca94113cedb028d3495f37da2::$classMap;

        }, null, ClassLoader::class);
    }
}
