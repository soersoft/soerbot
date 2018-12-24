<?php

namespace SoerBot;

use React\EventLoop\Factory;
use CharlotteDunois\Livia\LiviaClient;

class Runner
{
    /**
     * @var Factory
     */
    private $loop;

    /**
     * @var LiviaClient
     */
    private $client;

    /**
     * Runner constructor.
     *
     * @param null $client
     * @param null $loop
     * @throws Exceptions\ConfigurationFileNotFound
     */
    public function __construct($client = null, $loop = null)
    {
        $this->loop = $loop ?? $this->makeLoop();
        $this->client = $client ?? $this->makeClient();
    }

    public function execute()
    {
        $this->settings();
        $this->logReadyState();
        $this->login();
        $this->runningLoop();
    }

    /**
     * @return \React\EventLoop\LoopInterface
     */
    private function makeLoop()
    {
        return $this->loop = Factory::create();
    }

    /**
     * @return void
     */
    public function settings(): void
    {
        // Registers default commands, command groups and argument types
        $this->client->registry->registerDefaults();

        // Register the command group for our example command
        $this->client->registry->registerGroup(['id' => 'moderation', 'name' => 'Moderation']);

        // Register our commands (this is an example path)
        // TODO вынести регистрацию команд из файла в структуру.
        $this->client->registry->registerCommandsIn(__DIR__ . '/../commands/');
    }

    /**
     * @return void
     */
    public function logReadyState(): void
    {
        $this->client->on('ready', function () {
            echo 'Logged in as ' . $this->client->user->tag . ' created on ' .
                $this->client->user->createdAt->format('d.m.Y H:i:s') . PHP_EOL;
        });
    }

    /**
     * @throws Exceptions\ConfigurationFileNotFound
     * @return void
     */
    public function login(): void
    {
        $this->client
            ->login($this->config('key'))
            ->done();
    }

    /**
     * @return void
     */
    public function runningLoop(): void
    {
        $this->loop->run();
    }

    /**
     * @throws Exceptions\ConfigurationFileNotFound
     * @return LiviaClient
     */
    private function makeClient()
    {
        return new LiviaClient(
            $this->configurationForClient(),
            $this->loop
        );
    }

    /**
     * @throws Exceptions\ConfigurationFileNotFound
     * @return array
     */
    private function configurationForClient()
    {
        return [
            'owners' => $this->config('users'),
            'unknownCommandResponse' => false,
            'commandPrefix' => $this->config('command-prefix'),
        ];
    }

    /**
     * @param $key
     * @param null $default
     * @throws Exceptions\ConfigurationFileNotFound
     * @return mixed|null
     */
    private function config($key, $default = null)
    {
        return Configurator::get($key, $default);
    }
}
