<?php

namespace API\System
{
    class DirScanner
    {

      /**
         * File search, recursive, 
         * - returns list of full path to found files
         * - https://stackoverflow.com/questions/1860393/recursive-file-search-php
         * @param $rootDir Start dir to scan
         * - string
         * @param $regexPattern Search filter <regex>!
         * - string
         * @throws UnexpectedValueException
         * @return list of full path to found files
         *  - array
         */
        public static function FindFiles(string $rootDir, string $regexPattern = '/.+\.[pP][hH][pP]$/'):array
        {
            if (!is_string($rootDir) || !is_string($regexPattern))
              throw new UnexpectedValueException();

            $directoryIterator = new \RecursiveDirectoryIterator($rootDir);
            $flattenedIterator = new \RecursiveIteratorIterator($directoryIterator);
            $regexIterator     = new \RegexIterator($flattenedIterator, $regexPattern, \RecursiveRegexIterator::GET_MATCH);
            
            // print_r(iterator_to_array($regex));// debug

            $res = array();
            foreach ($regexIterator as $regexItem)
                $res[]=$regexItem[0];
            
            // print_r($res);// debug

            return $res;
        }

        /**
         * File search, recursive 
         * - returns collection like file info of found files
         *  - collection<string Key as fullPath, SplFileInfo<pathName,fileName> value> of foud files
         * - https://stackoverflow.com/questions/1860393/recursive-file-search-php
         * @param $rootDir Start dir to scan
         * - string
         * @param $regexPattern Search filter <regex>!
         * - string
         * @throws UnexpectedValueException
         * @return collection of foud files 
         *  - collection<string Key as fullPath, SplFileInfo<pathName,fileName> value> of foud files
         */
        public static function FindFilesInfo(string $rootDir, string $regexPattern = '/.+\.[pP][hH][pP]$/'):array
        {
            if (!is_string($rootDir) || !is_string($regexPattern))
              throw new UnexpectedValueException();

              // $directory = new \RecursiveDirectoryIterator($rootDir);
              // $iterator  = new \RecursiveIteratorIterator($directory);
              // $regex     = new \RegexIterator($iterator, '/\.php$/i');

            $directory = new \RecursiveDirectoryIterator($rootDir);
            $flattened = new \RecursiveIteratorIterator($directory);
            $regex     = new \RegexIterator($flattened, $regexPattern);

            // print_r(iterator_to_array($regex)); // debug

            return iterator_to_array($regex);
        }
    }
}