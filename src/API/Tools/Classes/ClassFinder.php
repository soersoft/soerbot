<?php  
namespace \API\Tools;

class ClassFinder implements IClassFinder
{
    /**
     * finder all registred classes are implemens interfaceName
     * thans to
     * - https://stackoverflow.com/questions/3993759/php-how-to-get-a-list-of-classes-that-implement-certain-interface
     * @property $interfaceName 
     * - name of looking interface\class (IClassFinder::class)
     * @return List<Type>
     * - type implement or instance of interfaceName
     */
    public static function findClasses(string $interfaceName)
    {
        return array_filter(
            get_declared_classes(),
            function( $className ) use ( $interfaceName ) {
                return in_array( $interfaceName, class_implements( $className ) );
            }
        );
    }
}