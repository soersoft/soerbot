<?php

namespace SoerBot\Commands\PhpFact\Implementations\Commands;

use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Exceptions\PhpFactException;
use SoerBot\Commands\PhpFact\Exceptions\CommandWrongUsageException;
use SoerBot\Commands\PhpFact\Abstractions\AbstractCommandWithArguments;

class SearchCommand extends AbstractCommandWithArguments
{
    /**
     * Minimum pattern length.
     */
    public const MIN_LENGTH = 3;
    /**
     * Maximum pattern length.
     */
    public const MAX_LENGTH = 32;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var array
     */
    private $found = [];

    /**
     * SearchCommand constructor.
     *
     * @param PhpFacts $facts
     * @param array $args
     * @throws CommandWrongUsageException|PhpFactException
     */
    public function __construct(PhpFacts $facts, array $args)
    {
        parent::__construct($facts, $args);

        $this->initPattern();
        $this->search();
    }

    /**
     * Returns command result.
     *
     * @return string
     */
    public function response(): string
    {
        if (empty($this->found)) {
            return 'Nothing found on ' . $this->pattern . ' request';
        }

        $response = '';

        if (($count = count($this->found)) > 1) {
            foreach ($this->found as $k => $v) {
                $response .= ($k + 1) . '. ' . $v;
                if (--$count) {
                    $response .= PHP_EOL;
                }
            }
        } else {
            $response = $this->found[0];
        }

        return $response;
    }

    /**
     * Checks if pattern is valid.
     *
     * @throws CommandWrongUsageException
     * @return void
     */
    protected function initPattern(): void
    {
        if (!isset($this->args['argument'])) {
            throw new CommandWrongUsageException('Wrong usage of search command. Check if you pass argument.');
        }

        $this->args['argument'] = trim($this->args['argument']);

        if (empty($this->args['argument'])) {
            throw new CommandWrongUsageException('Wrong usage of search command. Check if argument is empty.');
        }

        $length = mb_strlen($this->args['argument']);

        if ($length < self::MIN_LENGTH) {
            throw new CommandWrongUsageException('Wrong usage of search command. Argument is less than minimum ' . self::MIN_LENGTH . ' chars.');
        }

        if ($length > self::MAX_LENGTH) {
            throw new CommandWrongUsageException('Wrong usage of search command. Argument is more than maximum ' . self::MAX_LENGTH . ' chars.');
        }

        $this->pattern = $this->args['argument'];
    }

    /**
     * Search pattern in PhpFacts.
     *
     * @throws PhpFactException
     * @return void
     */
    protected function search(): void
    {
        $this->found = $this->facts->search($this->pattern);
    }
}
