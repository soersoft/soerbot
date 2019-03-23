<?php


$dir  = '../store';
$files = scandir($dir);

echo 'Топиков в базе: ';


foreach ($files as $list)
{

    echo $list . '<br>';
}

?>