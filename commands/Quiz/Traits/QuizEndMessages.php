<?php

namespace SoerBot\Commands\Quiz\Traits;

trait QuizEndMessages
{
    private $endMessages = [
    'Вот и все, игра сыграна.',
    'Игра сыграна, считаемся.',
  ];

    private function getEndMessage()
    {
        return $this->endMessages[array_rand($this->endMessages)];
    }
}
