<?php

namespace SoerBot\Commands\PhpFact;

use CharlotteDunois\Livia\CommandMessage;
use SoerBot\Commands\PhpFact\Implementations\CommandHelper;
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
            'aliases' => ['php'],
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
                    'prompt' => CommandHelper::getCommandDefaultMessage(),
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
            $storage = new FileStorage();
            $facts = new PhpFacts($storage);
        } catch (StorageException $e) {
            // Exception on storage level: log exception or notify admin with $e->getMessage()
            return $message->say(CommandHelper::getCommandErrorMessage());
        } catch (PhpFactException $e) {
            // Exception on class level: log exception or notify admin with $e->getMessage()
            return $message->say(CommandHelper::getCommandErrorMessage());
        } catch (\Throwable $e) {
            // Exception with emergency level: log exception or notify admin with $e->getMessage()
            return $message->say(CommandHelper::getCommandErrorMessage());
        }

        $parsed = trim($args['command']);

        if (empty($parsed)) {
            return $message->say(CommandHelper::getCommandDefaultMessage());
        }

        if (preg_match('/([a-z]+)(?:\s+(\d+))?$/iSu', $args['command'], $match)) {
            array_shift($match);

            switch ($match[0]) {
                case 'fact':
                    if (!empty($match[1])) {
                        $fact = $facts->get($match[1]) ? $facts->get($match[1]) : CommandHelper::getCommandFactNotFoundMessage($match[1]);

                        return $message->say($fact);
                    }

                    return $message->say($facts->getRandom());

                    break;
                case 'stat':
                    return $message->say('We have ' . ($facts->count() > 1 ? $facts->count() . ' facts' : $facts->count() . ' fact') . ' in collection.');

                    break;
                case 'list':
                    return $message->say(CommandHelper::getCommandDefaultMessage());

                    break;
                default:
                    return $message->say(CommandHelper::getCommandNotFoundMessage($args['command']));

                    break;
            }
        }

        return $message->say(CommandHelper::getCommandNotFoundMessage($args['command']));
    }
}
