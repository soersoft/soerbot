<?php

namespace SoerBot\Commands\Quiz\Traits;

trait QuizWelcomeMessages
{
    private $welcomeMessages = [
    'Привет бандиты! Не хотите поиграть?',
    'Вот мы и встретились, думаю пора сыграть!',
  ];

    private function getWelcomeMessage()
    {
        return $this->welcomeMessages[array_rand($this->welcomeMessages)];
    }
}
