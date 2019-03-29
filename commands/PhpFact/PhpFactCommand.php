<?php

namespace SoerBot\Commands\PhpFact;

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

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        try {
            $storage = new FileStorage();
            $fact = new PhpFacts($storage);
        } catch (StorageException $e) {
            // Exception on storage level: log exception or notify admin with $e->getMessage()
            return $this->getErrorMessage($message);
        } catch (PhpFactException $e) {
            // Exception on class level: log exception or notify admin with $e->getMessage()
            return $this->getErrorMessage($message);
        } catch (\Throwable $e) {
            // Exception with emergency level: log exception or notify admin with $e->getMessage()
            return $this->getErrorMessage($message);
        }

        switch ($args['command']) {
            case 'fact':
                return $message->say($fact->getRandom());

                break;
            case 'stat':
                return $message->say('We have ' . $fact->count() . ' facts in collection.');

                break;
            default:
                return $message->say($this->getDefaultMessage());

                break;
        }
    }

    public function serialize()
    {
        return [];
    }

    protected function getDefaultMessage()
    {
        return 'Input one of the command:' . PHP_EOL . 'fact - get random php fact' . PHP_EOL . 'stat - get php facts statistics';
    }

    protected function getErrorMessage(\CharlotteDunois\Livia\CommandMessage $message)
    {
        return $message->say('Something went wrong. Today without interesting PHP facts. Sorry!');
    }
}
