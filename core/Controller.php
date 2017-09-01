<?php

abstract class Controller
{
    protected function view($view, $data = [])
    {
        $this->startSession();
        extract($data);
        require 'views/partials/head.view.php';
        require "views/{$view}.view.php";
        require 'views/partials/foot.view.php';
    }

    protected function startSession()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}