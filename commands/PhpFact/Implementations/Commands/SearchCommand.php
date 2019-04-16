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
    public const PATTERN_MIN_LENGTH = 3;

    /**
     * Maximum pattern length.
     */
    public const PATTERN_MAX_LENGTH = 32;

    /**
     * Maximum response output length.
     */
    public const OUTPUT_MAX_LENGTH = 2000;

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
            return 'Nothing found with ' . $this->pattern . ' request.';
        }

        $count = count($this->found);

        if ($count === 1) {
            return $this->found[0];
        }

        $response = '';
        $maximum = self::OUTPUT_MAX_LENGTH - 4;

        foreach ($this->found as $k => $v) {
            if (mb_strlen($response . $v) > $maximum) {
                $response .= PHP_EOL . '...';

                break;
            }

            $response .= ($k + 1) . '. ' . $v;

            if (--$count) {
                $response .= PHP_EOL;
            }
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

        if ($length < self::PATTERN_MIN_LENGTH) {
            throw new CommandWrongUsageException('Wrong usage of search command. Argument is less than minimum ' . self::PATTERN_MIN_LENGTH . ' chars.');
        }

        if ($length > self::PATTERN_MAX_LENGTH) {
            throw new CommandWrongUsageException('Wrong usage of search command. Argument is more than maximum ' . self::PATTERN_MAX_LENGTH . ' chars.');
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
