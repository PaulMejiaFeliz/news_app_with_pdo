<?php namespace newsapp\core;

/**
 * Class used to display the pagination controls
 */
class Pagination
{
    /**
     * Displays the pagination controls
     *
     * @param int $count Total number of items
     * @param int $itemsPerPage Number of items per page
     * @param int $linksCount Maximun number of links in the pagination controls
     * @param int $current Current page
     * @return void
     */
    public static function load(int $count, int $itemsPerPage, int $linksCount, int $current) : void
    {
        if (!$count) {
            return;
        }
        
        $page = [];

        $page['url'] = Request::queryString();
        if ($page['url'] != null) {
            $strings = explode('&', $page['url']);
            $page['url'] = '';
            foreach ($strings as $s) {
                $string = explode('=', $s);
                if ($string[0] != 'page') {
                    $page['url'] .= "{$string[0]}={$string[1]}&";
                }
            }
            $page['url'] = Request::uri().'?'. $page['url'] . 'page=';
        } else {
            $page['url'] = Request::uri().'?page=';
        }

        $page['count'] = $count;
        $page['itemsPerPage'] = $itemsPerPage;
        $page['linksCount'] = $linksCount;
        $page['current'] = $current;

        $page['pageCount'] = ceil($page['count'] / $page['itemsPerPage']);
        $page['prevDisabled'] = $page['current'] == 1 ? 'disabled' : '';
        $page['nextDisabled'] = $page['current'] == $page['pageCount'] ? 'disabled' : '';
        $page['linksCount'] = floor($page['linksCount']/2);

        require 'views/partials/pagination.view.php';
    }
}
