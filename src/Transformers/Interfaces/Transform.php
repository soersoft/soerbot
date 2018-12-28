<?php

namespace SoerBot\Transformers\Interfaces;

interface Transform
{
    /**
     * Transform data to format.
     *
     * @param $data
     * @return mixed
     */
    public function transform($data);
}