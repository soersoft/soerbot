<?php

namespace SoerBot\Commands\Quiz\Traits;

trait QuizNextMessages
{
    private $nextMessages = [
    'Хорошо играем, продолжаем.',
    'Супер, а теперь вопрос посложнее.',
  ];

    private function getNextMessage()
    {
        return $this->nextMessages[array_rand($this->nextMessages)];
    }
}
