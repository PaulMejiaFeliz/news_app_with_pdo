<?php

require 'vendor/autoload.php';
require 'core/bootstrap.php';

use newsapp\core\App;
use newsapp\core\Router;
use newsapp\core\Request;
use newsapp\core\database\Connection;

App::bind('router', new Router('Home@notFound'));

require 'routes.php';

App::get('router')->direct(Request::uri(), Request::method());
Connection::closeConnection();
