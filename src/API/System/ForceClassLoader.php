<?php  

namespace API\System
{

    /**
     * force loading all classes
     * - initiate:
     *  - \API\System\ForceClassLoader::InitScan(__DIR__);
     * - example use:
     *  - \API\System\ForceClassLoader::Load();
     */
    class ForceClassLoader extends LasyNameSpace
    {
        /**
         * force loading all classes
         */
        public static function Load():void
        {
          foreach (self::$declalations as $item)
          {
            $fileName = $item[self::constFileName];

            // include file (force load)
            try { include_once($fileName); }
            catch (Exception $e) {}
          }
        }
    }
}