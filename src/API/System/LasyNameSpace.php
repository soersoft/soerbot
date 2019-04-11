<?php

namespace API\System
{

    /**
     * autoloadClass
     * - without needs to specify namespace for each class in header of unit
     * - initiate:
     *  - \API\System\LasyNameSpace::InitScan(__DIR__);
     * - example use:
     *  - include 'LasyNameSpace.php';
     */
    class LasyNameSpace
    {
        /**
         * Dictionary<sting,Dictionary<string,string>>
         * dictionary of 
         * - key
         *  - declaration name(class|abstract class|interface)
         * - value dictionary of key=>value
         *  - 'declarationName'=> declaration name(class|abstract class|interface)
         *  - 'namespace'=> namespace
         *  - 'fileName'=> fileName
         */
        protected static $declalations = array();
        const constDeclarationName = 'declarationName';
        const constNamespace = 'namespace';
        const constFileName = 'fileName';

        /**
         * Map classes position in folder structure
         */
        public static function InitScan(string $rootDir):void
        {
          $fileNames = DirScanner::FindFiles($rootDir);
          $parcerPHP = new FileParcerPHP();

          self::$declalations = array();
          foreach ($fileNames as $fileName)
          {
            $namespace = $parcerPHP->getNamespaceFromFile($fileName);

            $declarationNames = $parcerPHP->getAnyDeclarationsFromFile($fileName);
            if (count($declarationNames)>0)
                foreach ($declarationNames as $declarationName)
                {
                    $item=[
                      self::constDeclarationName => $declarationName, 
                      self::constNamespace => $namespace, 
                      self::constFileName => $fileName
                    ];
                    self::$declalations[$declarationName]=$item;
                }
          }
        }
        /**
         * autoload class, 
         * - without needs to specify namespace for each class in header of unit
         * 
         * example use:
         * - include 'LasyNameSpace.php';
         * 
         * @param $className class to load
         * - string
         * @param $currentNamespace = __NAMESPACE__ current namespace
         * - string
         * @throws UnexpectedValueException
         */
        public static function LazyLoad(string $declarationName, string $currentNamespace = __NAMESPACE__):void
        {
          // get correct declaration name
          if (strpos($mystring, $findme) === true)
            $className = substr($className, strlen($currentNamespace));

          // get file name
          $item = self::$declalations[$declarationName];
          $fileName = $item[self::constFileName];

          // include file
          try { include_once($fileName); }
          catch (Exception $e) {}
        }
    }

    // magic aoutoload )))
    spl_autoload_register('API\System\LasyNameSpace::LazyLoad');
}
