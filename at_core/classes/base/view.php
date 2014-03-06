<?php

namespace classes\base;

use ATCore;

class ATCore_View
{
    public static $layout = 'layout';

    public static function load($name, $data = array(), $_return = false)
    {
        if (!empty($data)) {
            extract($data);
        }

        ob_start();
        ob_implicit_flush(false);
        require 'view/' . $name . '.php';
        $content = ob_get_clean();

        if ($_return) {
            return $content;
        } else {
            $layout = static::$layout;
            if ($layout && is_file('view/' . $layout . '.php')) {
                echo static::load($layout, array('content' => $content), true);
            } else {
                echo $content;
            }
        }
        return false;
    }

    public static function pagination($page, $show, $count, $link_tmpl = 'ID')
    {
        $clear_link = str_replace('ID', '', str_replace('?', '', $link_tmpl));
        $clear_link = explode('/', $clear_link);
        $clear_link = array_pop($clear_link);

        if (!empty($clear_link) && strstr($clear_link, '=')) {
            $query_string = ATCore::$serv->query_string;
            $query_string = preg_replace('/(&|)' . $clear_link . $page . '/', '', $query_string);

            preg_match('/' . $clear_link . '/', $query_string, $matches);

            if (!empty($query_string) && count($matches) == 0) {
                $query_string = '?' . $query_string;
                $link_tmpl = str_replace('?', $query_string . '&', $link_tmpl);
            }
        } else {
            $query_string = '';

            if (!empty(ATCore::$serv->query_string)) {
                $query_string = '?' . ATCore::$serv->query_string;
            }

            $link_tmpl = $link_tmpl . $query_string;
        }

        if ($count <= $show) {
            return;
        }

        $show_link = 7;
        $start = $page - (ceil($show_link / 2));
        $finish = $page + (floor($show_link / 2));
        $page_count = ceil($count / $show);

        if ($page <= (floor($show_link / 2))) {
            $finish = $show_link;
        }

        if ($page > ($page_count - floor($show_link / 2))) {
            $start = $start - ((floor($show_link / 2)) - ($page_count - $page));
        }

        if ($page <= (ceil($show_link / 2)) || $page_count <= $show_link) {
            $tmpl = '<ul class="pagination"><li class="disabled"><a>В начало</a></li>';
        } else {
            $tmpl = '<ul class="pagination"><li><a href="' . str_replace('ID', '1', $link_tmpl) . '">В начало</a></li>';
        }

        for ($i = 1; $i <= $page_count; $i++) {
            if ($i > $start && $i <= $finish) {
                $tmpl .= '<li ' . (($page == $i) ? 'class="active"' : '') . '><a href="' . str_replace('ID', $i, $link_tmpl) . '">' . $i . '</a></li>';
            } elseif ($i < $finish) {
                $tmpl .= '<li><a href="' . str_replace('ID', $start, $link_tmpl) . '">...</a></li>';
                $i = $start;
            } else {
                $tmpl .= '<li><a href="' . str_replace('ID', ($finish + 1), $link_tmpl) . '">...</a></li>';
                break;
            }
        }

        if ($page >= ($page_count - floor($show_link / 2)) || $page_count <= $show_link) {
            $tmpl .= '<li class="disabled"><a>В конец</a></li></ul>';
        } else {
            $tmpl .= '<li><a href="' . str_replace('ID', $page_count, $link_tmpl) . '">В конец</a></li></ul>';
        }

        echo $tmpl;
    }
}

?>