<?php

namespace Tests\Commands\Quiz;

use Tests\TestCase;
use SoerBot\Commands\Quiz\Services\QuizStoreJSONFile;

class QuizStoreTest extends TestCase
{
    private $store;

    protected function setUp()
    {
        $this->store = new QuizStoreJSONFile();

        parent::setUp();
    }

    public function testAddFunction()
    {
        $this->setPrivateVariableValue($this->store, 'data', []);
        $this->store->add(['Вопрос', 'Ответ', 'Теги']);
        $result = $this->getPrivateVariableValue($this->store, 'data');
        $this->assertEquals($result, [['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']]);
    }

    public function testGetFunction()
    {
        $this->setPrivateVariableValue($this->store, 'data', [['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']]);
        $this->assertEquals($this->store->get(), ['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']);
    }

    public function testLoadFunction()
    {
        $this->setPrivateVariableValue($this->store, 'file', __DIR__ . '/../../Fixtures/quiz.json');
        $this->store->load();

        $result = $this->getPrivateVariableValue($this->store, 'data');
        $this->assertEquals($result, [['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']]);
    }

    public function testSaveFunction()
    {
        $filePath = __DIR__ . '/../../Fixtures/quiz.tmp.json';
        $expectedResult = [['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']];

        $this->setPrivateVariableValue($this->store, 'file', $filePath);
        $this->setPrivateVariableValue($this->store, 'data', $expectedResult);
        $this->store->save();

        $result = json_decode(file_get_contents($filePath), true);
        $this->assertEquals($result, $expectedResult);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
