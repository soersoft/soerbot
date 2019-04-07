<?php

namespace SoerBot\Commands\PhpFact\Abstractions;

interface CommandInterface
{
    /**
     * Returns command result.
     *
     * @return string
     */
    public function response(): string;
}
