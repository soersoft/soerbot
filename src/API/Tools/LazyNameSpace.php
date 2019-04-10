<?php  

namespace API\Tools;

/**
 * autoloadClass
 * komposer file fix https://klisl.com/composer_autoload.html
 */
class LasyNameSpace
{

    /**
     * ready 
     * - https://gist.github.com/Nilpo/5de133d2ab7a025bebeb
     * File search
     * - https://ruseller.com/lessons.php?id=1575&rub=37
     * - https://stackoverflow.com/questions/1860393/recursive-file-search-php
     * Get Namespace current
     * - https://www.php.net/manual/ru/language.namespaces.nsconstants.php
     * Get namespace dir
     * - https://stackoverflow.com/questions/46555446/php-7-1-get-file-path-from-namespace
     * Autoload
     * - https://stackoverflow.com/questions/7651509/what-is-autoloading-how-do-you-use-spl-autoload-autoload-and-spl-autoload-re
     * - 
     */
    public static function FindAndLoadClass(Closure $function):void
    {
      $directory = new RecursiveDirectoryIterator('path/to/directory/');
      $iterator  = new RecursiveIteratorIterator($directory);
      $regex     = new RegexIterator($iterator, '/\.jpe?g$/i', RecursiveRegexIterator::GET_MATCH);

      echo '<pre>';
      print_r($regex);
        if (!($function instanceof Closure))
          throw new UnexpectedValueException();

        // add value to array
        // https://stackoverflow.com/questions/676677/how-to-add-elements-to-an-empty-array-in-php
        $this->_eventHandlers[] = $function; // this way 
        // array_push($this->_eventHandlers, $function); // or this (like to stack)
    }

    /**
     * launch event
     * - call all eventHandlers for this event
     * 
     * @param $arg - list parameters to function
     *  - untyped array
     *    - danger can be exceptions, due use uncontrolled values
     */
    public function eventLaunch (array $arg = null):void
    {
        foreach ($this->_eventHandlers as $eventHandler) 
          call_user_func($eventHandler, $arg);
    }

}