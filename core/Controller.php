<?php namespace newsapp\core;

/**
 * Base class of creating controllers
 */
abstract class Controller
{
    /**
     * Displays the given view within the header and footer of the page
     *
     * @param string $view name off the view ta will be diplayed
     * @param array $data variables that the view will use
     * @return void
     */
    protected function view(string $view, array $data = []) : void
    {
        $this->startSession();
        extract($data);
        require 'views/partials/head.view.php';
        require "views/{$view}.view.php";
        require 'views/partials/foot.view.php';
    }

    /**
     * If the session isn't started, starts it
     *
     * @return void
     */
    protected function startSession() : void
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
