<?php

$router = App::get('router');
$router->get('', 'Home@index');
$router->get('logout', 'Account@logout');
$router->get('login', 'Account@login');
$router->get('register', 'Account@register');
$router->get('newPost', 'Home@newPost');
$router->get('postDetails', 'Home@postDetails');
$router->get('editPost', 'Home@editPost');
$router->get('myPosts', 'Home@myPosts');

$router->post('login', 'Account@loginPost');
$router->post('register', 'Account@registerPost');
$router->post('newPost', 'Home@addPost');
$router->post('editPost', 'Home@modifyPost');
$router->post('deletePost', 'Home@deletePost');
$router->post('addComment', 'Comments@addComment');
$router->post('editComment', 'Comments@editComment');
$router->post('deleteComment', 'Comments@deleteComment');
