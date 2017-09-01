<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";
require "core/bootstrap.php";

App::bind('router', new Router("Home@notFound"));

require "routes.php";

// $id = App::get('qBuilder')->insert(
//     'news',
//     [
//         'title' => 'Saludo',
//         'content' => 'Hola, ¿Qué tal??',
//         'user' => 1,
//         'created_at' => date('Y-m-d H:i:s')
//     ]
// );

// var_dump($id);
//  echo '<pre>';
// var_dump(App::get('qBuilder')->select(
//     "news"
// ));
// echo '</pre>';
//
// var_dump(App::get('qBuilder')->update(
//     'news',
//     3,
//     [
//         'title' => 'Saludo',
//         'content' => 'Hola, ¿Qué tal??',
//         'user' => 1,
//         'updated_at' => date('Y-m-d H:i:s')
//     ]
// ));

App::get('router')->direct(Request::uri(), Request::method());

App::get('connection')->close();
