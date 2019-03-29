<?php

namespace SoerBot\Commands\PhpFact;

use CharlotteDunois\Livia\CommandMessage;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Exceptions\PhpFactException;
use SoerBot\Commands\PhpFact\Exceptions\StorageException;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;

class PhpFactCommand extends \CharlotteDunois\Livia\Commands\Command
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'phpfact', // Give command name
            'aliases' => ['fact'],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Show PHP facts from https://github.com/pqr/5minphp-bot.', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'args' => [
                [
                    'key' => 'command',
                    'label' => 'command',
                    'prompt' => $this->getDefaultMessage(),
                    'type' => 'string',
                ],
            ],
        ]);
    }

    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        return $this->commandHandler($message, $args);
    }

    public function serialize()
    {
        return [];
    }

    /**
     * Parse args and return result.
     *
     * @param CommandMessage $message
     * @param \ArrayObject $args
     * @return \React\Promise\ExtendedPromiseInterface
     */
    protected function commandHandler(CommandMessage $message, \ArrayObject $args)
    {
        try {
            $facts = $this->initFacts();
        } catch (StorageException $e) {
            // Exception on storage level: log exception or notify admin with $e->getMessage()
            return $message->say($this->getErrorMessage());
        } catch (PhpFactException $e) {
            // Exception on class level: log exception or notify admin with $e->getMessage()
            return $message->say($this->getErrorMessage());
        } catch (\Throwable $e) {
            // Exception with emergency level: log exception or notify admin with $e->getMessage()
            return $message->say($this->getErrorMessage());
        }

        $parsed = trim($args['command']);

        if (empty($parsed)) {
            return $message->say($this->getDefaultMessage());
        }

        if (preg_match('/([a-z]+)(?:\s+(\d+))?$/iSu', $args['command'], $match)) {
            array_shift($match);

            switch ($match[0]) {
                case 'fact':
                    if (!empty($match[1])) {
                        $fact = $facts->get($match[1]) ? $facts->get($match[1]) : $this->getFactNotFoundMessage($match[1]);

                        return $message->say($fact);
                    }

                    return $message->say($facts->getRandom());

                    break;
                case 'stat':
                    return $message->say('We have ' . ($facts->count() > 1 ? $facts->count() . ' facts' : $facts->count() . ' fact') . ' in collection.');

                    break;
                case 'list':
                    return $message->say($this->getDefaultMessage());

                    break;
                default:
                    return $message->say($this->getCommandNotFoundMessage($args['command']));

                    break;
            }
        }

        return $message->say($this->getCommandNotFoundMessage($args['command']));
    }

    /**
     * Initialize object.
     *
     * @throws \Exception|PhpFactException
     * @return PhpFacts
     */
    protected function initFacts(): PhpFacts
    {
        $storage = new FileStorage();

        return new PhpFacts($storage);
    }

    protected function getDefaultMessage()
    {
        return 'Input one of the command:' . PHP_EOL . 'fact - get random php fact' . PHP_EOL . 'fact [num] - get php fact by number' . PHP_EOL . 'stat - get php facts statistics' . PHP_EOL . 'list - list all possible command';
    }

    protected function getErrorMessage()
    {
        return 'Something went wrong. Today without interesting PHP facts. Sorry!';
    }

    protected function getCommandNotFoundMessage(string $command)
    {
        return 'The ' . $command . ' is wrong command. Use $phpfact list for right command list.';
    }

    protected function getFactNotFoundMessage(string $position)
    {
        return 'The ' . $position . ' is wrong fact. Use $phpfact stat to find right position number.';
    }
}
