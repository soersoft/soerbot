<?php

namespace API\System;


class DirScanner
{
   /**
     * File search, recursive
     * - https://stackoverflow.com/questions/1860393/recursive-file-search-php
     * @param $rootDir Start dir to scan
     * - string
     * @param $regexPattern Search filter <regex>!
     * - string
     * @throws UnexpectedValueException
     * @return array of fouded files
     */
    public static function FindFiles(string $rootDir, string $regexPattern = '.+\.[pP][hH][pP]'):array
    {
        if (!is_string($rootDir) || !is_string($regexPattern))
          throw new UnexpectedValueException();
        $directory = new \RecursiveDirectoryIterator($rootDir);
        $flattened = new \RecursiveIteratorIterator($directory);
        $regex     = new \RegexIterator($flattened, $regexPattern, RecursiveRegexIterator::GET_MATCH);
        
        return $regex;
    }
}
