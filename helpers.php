<?php

if ( ! function_exists('is_not_null')) {
    function is_not_null($var): bool
    {
        return ! is_null($var);
    }
}

if ( ! function_exists('set_query_filters_request')) {
    function set_query_filters_request($key, $value)
    {
        if( ! isset($GLOBALS['query_filters_requests']))
            $GLOBALS['query_filters_requests'] = array();

        $GLOBALS['query_filters_requests'][$key] = $value;
    }
}

if ( ! function_exists('get_query_filters_request')) {
    function get_query_filters_request($key)
    {
        return isset($GLOBALS['query_filters_requests'])
            && isset($GLOBALS['query_filters_requests'][$key])
                ? $GLOBALS['query_filters_requests'][$key]
                : request($key);
    }
}

if ( ! function_exists('has_any_query_filters_request')) {
    function has_any_query_filters_request(array $keys)
    {
        $has = false;

        foreach($keys as $key)
        {
            if((isset($GLOBALS['query_filters_requests']) && isset($GLOBALS['query_filters_requests'][$key])) || request()->has($key))
            {
                $has = true;

                break;
            }
        }

        return $has;
    }
}