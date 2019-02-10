<?php

$q = file_get_contents('./quiz.txt');
$parts = explode("\n", $q);
$question = '';
$answer = '';
$s = [];
foreach ($parts as $key => $part) {
    if ($key % 3 == 0) {
        $question = trim($part);
    }
    if ($key % 3 == 1) {
        $answer = trim($part);
    }
    if ($key % 3 == 2) {
        array_push($s, ['question' => $question, 'answer' => $answer, 'tags' => '']);
    }
}

file_put_contents('questions.json', json_encode($s));
