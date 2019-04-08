<?php

$q = file_get_contents('./voting.txt');
$parts = explode("\n", $q);
$voting = '';
$answer = '';
$s = [];
foreach ($parts as $key => $part) {
    if ($key % 2 == 0) {
        $voting = trim($part);
    }
    if ($key % 2 == 1) {
        $question = trim($part);
    }
    if ($key % 2 == 2) {
        array_push($s, ['voting' => $voting, 'question' => $question]);
    }
}

file_put_contents('voting.json', json_encode($s));
