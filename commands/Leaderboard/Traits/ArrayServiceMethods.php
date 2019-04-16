<?php

namespace SoerBot\Commands\Leaderboard\Traits;

trait ArrayServiceMethods
{
    /**
     * Returns true if necessary key=>value exists.
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    protected function exists(array $array, $key, $value)
    {
        return !empty($this->where($array, function ($item) use ($key, $value) {
            return $item[$key] === $value;
        }));
    }

    /**
     * Looks for key with chosen value.
     * @param $array
     * @param $column
     * @param $value
     * @return false|int|string
     */
    protected function findKey($array, $column, $value)
    {
        return array_search($value, array_column($array, $column));
    }

    /**
     * Convenient way for array filtering.
     * @param array $array
     * @param callable $callback
     * @return array
     */
    protected function where(array $array, callable $callback)
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Returns the first item which suitable for condition.
     * @param array $array
     * @param callable|null $callback
     * @return mixed|null
     */
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
