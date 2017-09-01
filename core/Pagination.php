<?php

class Pagination
{
    public static function load(int $count, int $itemsPerPage, int $linksCount, int $current) : void
    {
        $paginationConfig = [];

        $paginationConfig['url'] = Request::queryString();
        if ($paginationConfig['url'] != null) {
            $strings = explode('&', $paginationConfig['url']);
            $paginationConfig['url'] = '';
            foreach ($strings as $s) {
                $string = explode('=', $s);
                if ($string[0] != 'page') {
                    $paginationConfig['url'] .= "{$string[0]}={$string[1]}&";
                }
            }
            $paginationConfig['url'] = Request::uri().'?'. $paginationConfig['url'] . 'page=';
        } else {
            $paginationConfig['url'] = Request::uri().'?page=';
        }

        $paginationConfig['count'] = $count;
        $paginationConfig['itemsPerPage'] = $itemsPerPage;
        $paginationConfig['linksCount'] = $linksCount;
        $paginationConfig['current'] = $current;

        $paginationConfig['pageCount'] = ceil($paginationConfig['count'] / $paginationConfig['itemsPerPage']);
        $paginationConfig['prevDisabled'] = $paginationConfig['current'] == 1 ? 'disabled' : '';
        $paginationConfig['nextDisabled'] = $paginationConfig['current'] == $paginationConfig['pageCount'] ? 'disabled' : '';
        $paginationConfig['linksCount'] = floor($paginationConfig['linksCount']/2);

        require 'views/partials/pagination.view.php';
    }
}