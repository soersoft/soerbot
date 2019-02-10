<?php

namespace SoerBot\Commands\Quiz\Traits;

trait QuizWrongAnswerMessages
{
    private $wrongAnswerMessages = [
    'Вижу правильного ответа никто не знает, подсказываю: ',
    'Правильный ответ: ',
  ];

    private function getWrongAnswerMessage()
    {
        return $this->wrongAnswerMessages[array_rand($this->wrongAnswerMessages)];
    }
}
