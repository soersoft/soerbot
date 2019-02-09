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
        // Подменяем store через reflection
        $reflection = new \ReflectionClass($this->store);
        $storeProperty = $reflection->getProperty('data');
        $storeProperty->setAccessible(true);
        $storeProperty->setValue($this->store, []);

        $this->store->add(['Вопрос', 'Ответ', 'Теги']);

        $result = $storeProperty->getValue($this->store);
        $this->assertEquals($result, [['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']]);
    }

    public function testGetFunction()
    {
        // Подменяем store через reflection
        $reflection = new \ReflectionClass($this->store);
        $storeProperty = $reflection->getProperty('data');
        $storeProperty->setAccessible(true);
        $storeProperty->setValue($this->store, [['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']]);

        $this->assertEquals($this->store->get(), ['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']);
    }

    public function testLoadFunction()
    {
        $reflection = new \ReflectionClass($this->store);

        $storeFileProperty = $reflection->getProperty('file');
        $storeFileProperty->setAccessible(true);
        $storeFileProperty->setValue($this->store, __DIR__ . '/../../Fixtures/quiz.json');

        $this->store->load();

        $storeDataProperty = $reflection->getProperty('data');
        $storeDataProperty->setAccessible(true);
        $result = $storeDataProperty->getValue($this->store);
        $this->assertEquals($result, [['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']]);
    }

    public function testSaveFunction()
    {
        $filePath = __DIR__ . '/../../Fixtures/quiz.tmp.json';
        $expectedResult = [['question' => 'Вопрос', 'answer' => 'Ответ', 'tags' => 'Теги']];

        $reflection = new \ReflectionClass($this->store);

        $storeFileProperty = $reflection->getProperty('file');
        $storeFileProperty->setAccessible(true);
        $storeFileProperty->setValue($this->store, $filePath);

        $storeDataProperty = $reflection->getProperty('data');
        $storeDataProperty->setAccessible(true);
        $storeDataProperty->setValue($this->store, $expectedResult);

        $this->store->save();

        $result = json_decode(file_get_contents($filePath), true);
        $this->assertEquals($result, $expectedResult);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
