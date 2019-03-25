<?php

namespace SoerBot\Commands\PhpFact\Exceptions;

class PhpFactStorageException extends \Exception
{
    protected $message = 'Something wrong with storage model.';
}