<?php

namespace SoerBot\Commands\PhpFact;

use CharlotteDunois\Livia\CommandMessage;
use SoerBot\Commands\PhpFact\Exceptions\CommandWrongUsageException;
use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Exceptions\CommandException;
use SoerBot\Commands\PhpFact\Exceptions\PhpFactException;
use SoerBot\Commands\PhpFact\Exceptions\StorageException;
use SoerBot\Commands\PhpFact\Implementations\FileStorage;
use SoerBot\Commands\PhpFact\Implementations\CommandHelper;
use SoerBot\Commands\PhpFact\Implementations\CommandFactory;
use SoerBot\Commands\PhpFact\Exceptions\CommandNotFoundException;

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
        $parsed = trim($args['command']);

        if (empty($parsed)) {
            return $message->say(CommandHelper::getCommandDefaultMessage());
        }

        try {
            $storage = new FileStorage();
            $facts = new PhpFacts($storage);
            $command = CommandFactory::build($facts, $parsed);
        } catch (CommandWrongUsageException $e) {
            return $message->say($e->getMessage());
        } catch (CommandNotFoundException $e) {
            return $message->say($e->getMessage());
        } catch (PhpFactException $e) {
            // Exception on class level: log exception or notify admin with $e->getMessage()
            return $message->say(CommandHelper::getCommandErrorMessage());
        } catch (StorageException $e) {
            // Exception on storage level: log exception or notify admin with $e->getMessage()
            return $message->say(CommandHelper::getCommandErrorMessage());
        } catch (\Throwable $e) {
            // Exception with emergency level: log exception or notify admin with $e->getMessage()
            return $message->say(CommandHelper::getCommandErrorMessage());
        }

        return $message->say($command->response());
    }

    public function serialize()
    {
        return [];
    }
}
