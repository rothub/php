<?php

if (!function_exists('dd')) {
    function dd($expression)
    {
        echo '<pre>';
        print_r($expression);
        echo '</pre>';
        exit;
    }
}

if (!function_exists('dr')) {
    function dr($expression)
    {
        $res = '';
        $map = unserialize($expression);
        foreach ($map as $key => $value) {
            $res .= $key . ': ' . $value . '<br/>';
        }

        echo '<pre>';
        print_r($res);
        echo '</pre>';
        exit;
    }
}
