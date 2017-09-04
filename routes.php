<?php

use newsapp\core\App;

$router = App::get('router');
$router->get('', 'Home@index');
$router->get('logout', 'Account@logout');
$router->get('login', 'Account@login');
$router->get('register', 'Account@register');
$router->get('postDetails', 'Home@postDetails');
$router->get('newPost', 'Home@addPostView');
$router->get('editPost', 'Home@editPostView');
$router->get('myPosts', 'Home@myPosts');

$router->post('login', 'Account@loginPost');
$router->post('register', 'Account@registerPost');
$router->post('newPost', 'Home@addPost');
$router->post('editPost', 'Home@editPost');
$router->post('deletePost', 'Home@deletePost');
$router->post('addComment', 'Comments@addComment');
$router->post('editComment', 'Comments@editComment');
$router->post('deleteComment', 'Comments@deleteComment');
