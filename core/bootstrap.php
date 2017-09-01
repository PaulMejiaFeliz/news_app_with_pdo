<?php

App::bind('config', require 'config.php');

App::bind(
    'connection',
    Connection::getConnection(App::get('config')['database'])
);

App::bind(
    'qBuilder',
    new QueryBuilder(App::get('connection'))
);
