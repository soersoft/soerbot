<?php

namespace SoerBot\Commands\Quiz\Traits;

trait QuizCorrectAnswerMessages
{
    private $correctAnswerMessages = [
    'В точку! Да!',
    'Да, откуда ты все знаешь?',
  ];

    private function getCorrectAnswerMessage()
    {
        return $this->correctAnswerMessages[array_rand($this->correctAnswerMessages)];
    }
}
