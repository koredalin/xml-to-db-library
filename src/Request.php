<?php

namespace Library;

/**
 * Description of Request
 *
 * @author H1
 */
class Request
{
    public function get($key): string
    {
        return trim(filter_input(INPUT_GET, $key));
    }

    public function post($key): string
    {
        return trim(filter_input(INPUT_POST, $key));
    }

    public function getNullableInt($key): ?int
    {
        return filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    public function postNullableInt($key): ?int
    {
        return filter_input(INPUT_PUT, $key, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }
}
