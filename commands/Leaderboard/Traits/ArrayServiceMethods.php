<?php

namespace SoerBot\Commands\Leaderboard\Traits;

trait ArrayServiceMethods
{
    protected function exists(array $array, $key, $value)
    {
        return !empty($this->where($array, function ($item) use ($key, $value) {
            return $item[$key] === $value;
        }));
    }

    protected function where(array $array, callable $callback)
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    protected function first(array $array, callable $callback = null)
    {
        if ($callback === null) {
            if (empty($array)) {
                return null;
            }

            //If callback doesn't define, we will return the first item
            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }

        return null;
    }
}
