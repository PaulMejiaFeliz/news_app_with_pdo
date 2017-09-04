<?php

use newsapp\core\App;
use newsapp\core\database\Connection;
use newsapp\core\database\QueryBuilder;

App::bind('config', require 'config.php');

App::bind(
    'connection',
    Connection::getConnection(App::get('config')['database'])
);

App::bind(
    'qBuilder',
    new QueryBuilder(App::get('connection'))
);
