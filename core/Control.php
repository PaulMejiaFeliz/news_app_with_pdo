<?php namespace newsapp\core;

use newsapp\core\Request;

/**
 * Class used to display different controls
 */
class Control
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
    public static function loadPagination(int $count, int $itemsPerPage, int $linksCount, int $current) : void
    {
        if (!$count) {
            return;
        }
        
        $page = [];

        $page['url'] = Request::addQueryString(['p' => '']);

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

    /**
     * Retrieves an a tag for an order by column title
     *
     * @param string $label Labe of the a tag
     * @param string $value Name of the column to order by
     * @return string
     */
    public static function loadOrderByAnchor(string $label, string $value) : string
    {
        $order = [ 'o' => $value ];
        $icon = '';
        $order['r'] = 'false';
        if (isset($_GET['o']) && $_GET['o'] == $value) {
            if (isset($_GET['r']) && $_GET['r'] == 'true') {
                $icon = '<i class=\'glyphicon glyphicon-arrow-up\'></i>';
            } else {
                $order['r'] = 'true';
                $icon = '<i class=\'glyphicon glyphicon-arrow-down\'></i>';
            }
            
        }
        return '<a href=\'' . Request::addQueryString($order) . "'>{$label} {$icon}</a>";
    }
}
