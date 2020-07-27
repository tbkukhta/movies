<?php

class UrlHelper
{
    /**
     * Generate url
     *
     * @param string $title
     * @param string $star
     * @param string $sort
     * @param string $page
     * @return string
     */
    public static function buildUrl($title = '', $star = '', $sort = 'id', $page = '1')
    {
        $data = array(
            'title' => $title,
            'star' => $star,
            'sort' => $sort,
            'page' => $page
        );
        return "index.php?" . http_build_query($data);
    }
}