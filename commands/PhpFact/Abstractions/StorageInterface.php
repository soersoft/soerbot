<?php

namespace SoerBot\Commands\PhpFact\Abstractions;

interface StorageInterface
{
    /**
     * Returns array of facts from some source.
     *
     * @return array
     */
    public function get(): array;
}
