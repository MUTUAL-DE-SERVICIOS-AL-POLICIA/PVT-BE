<?php


    function isActiveRoute($route, $output = 'active')
    {
        if (is_array($route)) {
            if (in_array(Route::currentRouteName(), $route)) {
                return $output;
            }
        } else {
            if (Route::currentRouteName() == $route) {
                return $output;
            }
        }
    }
